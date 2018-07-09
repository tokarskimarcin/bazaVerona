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
        $titles = fgetcsv($file, 1000);
        while (($row = fgetcsv($file, 1000)) !== false)
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
            for($i = 0; $i < count($phoneNr); $i++){
                if($phoneNr[$i] == $item->telefon){
                    unset($phoneNr[$i]);
                    break;
                }
            }
        });
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
    //
}
