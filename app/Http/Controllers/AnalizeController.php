<?php

namespace App\Http\Controllers;

use App\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class AnalizeController extends Controller
{
    public function getPhonenumberZipCodes(){
        return view('analize.phonenumberZipCodes');
    }

    private function getCollectionOfPhoneNumberWithZipCodes($path){
        $phoneNr = [];
        $file = fopen($path, 'r');
        $titles = fgetcsv($file);
        while (($row = fgetcsv($file)) !== false)
        {
            array_push($phoneNr,$row[0]);
        }
        fclose($file);


        $records = DB::table('rekordy')
            ->select('telefon','kodpocztowy')
            ->join('kod','rekordy.idkod','=','kod.idkod')
            ->whereIn('telefon',$phoneNr)
            ->get();
        $records->each(function ($item, $key) use (&$phoneNr){
            foreach ($phoneNr as $key => $number){
                if($number == $item->telefon){
                    unset($phoneNr[$key]);
                    break;
                }
            }        });
        foreach($phoneNr as $number){
            $records->push((object)['telefon'=>intval($number),'kodpocztowy'=>'Tego numeru nie ma w bazie']);
        }

        return $records;
    }


    public function postPhonenumberZipCodes(Request $request){

        if($request->file('import_file') !== null) {
            $path = $request->file('import_file')->getRealPath();
            $records = $this->getCollectionOfPhoneNumberWithZipCodes($path);
            return Redirect::back()->with('records', $records);
        }else
            return Redirect::back();


    }

    public function phoneNumberTextGet() {

        return view('analize.phoneNumberText');
    }

    public function phoneNumberTextPost(Request $request) {
        $date = $request->date; // YYYY-MM-DD
        $time = $request->time; //HH:MM
        $contents = $request->contents; //string

        if($request->file('import_file') !== null) {
            $path = $request->file('import_file')->getRealPath(); // for example: C:\xampp\tmp\php6E3A.tmp
            $this->createCSVWithUserData($path,$date,$time,$contents);
        }else
            return Redirect::back();
    }

    /**
     * @param $path
     * @param $date
     * @param $time
     * @param $text
     * @return mixed
     */
    private function createCSVWithUserData($path, $date, $time, $text) {
        $file = fopen($path, 'r');
        $titles = fgetcsv($file);
        $row = 1;
        $completeLinesArray = array();
        $lineArray = array();
        while (($data = fgetcsv($file)) !== false)
        {
            $lineArray = [];
            array_push($lineArray,str_replace(';','',$data[0]));
            $dateTime = $date . ' ' . $time;
            array_push($lineArray, $dateTime . ":00");
            array_push($lineArray,$text);
            array_push($completeLinesArray, $lineArray);
        }
        fclose($file);
        $napis="plik wynikowy";
        return Excel::create($napis, function ($excel) use ($completeLinesArray) {
            $excel->sheet('sheet1', function ($sheet) use ($completeLinesArray) {
                $sheet->fromArray($completeLinesArray, null, 'A1', false, false);
            });
        })->export('csv');
    }
}
