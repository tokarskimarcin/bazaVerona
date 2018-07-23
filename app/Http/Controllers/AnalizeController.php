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
        // pobranie wszystkich wierszy z pierwszej kolumny
        $phoneNr = [];
        $file = fopen($path, 'r');
        $titles = fgetcsv($file);
        while (($row = fgetcsv($file)) !== false)
        {
            array_push($phoneNr,$row[0]);
        }
        fclose($file);

        //okreslenie idbazy
        foreach ([8,38] as $key){
            $baza[$key] = 'bisnode';
        }
        foreach ([5,9,17] as $key){
            $baza[$key] = 'stare zgody';
        }
        foreach ([1,2,3,4,7,10,11,12,13,14,15,16,18] as $key){
            $baza[$key] = 'reszta';
        }
        foreach ([6] as $key){
            $baza[$key] = 'event';
        }
        foreach ([19] as $key){
            $baza[$key] = 'exito';
        }
        foreach ([28,48] as $key){
            $baza[$key] = 'zgody bisnode';
        }
        foreach ([27] as $key){
            $baza[$key] = 'nowe zgody';
        }
        foreach ([24] as $key){
            $baza[$key] = 'zgody reszta';
        }
        foreach ([26] as $key){
            $baza[$key] = 'zgody event';;
        }
        foreach ([29] as $key){
            $baza[$key] = 'zgody exito';
        }

        //$duplicatePhoneNr = [];
        $records = DB::table('rekordy')
            ->select('telefon', 'kodpocztowy', 'idbaza')
            ->join('kod', 'rekordy.idkod', '=', 'kod.idkod')
            ->whereIn('telefon', $phoneNr)
            ->get();
        $records->each(function ($item, $key) use (&$phoneNr, $baza){ //, &$duplicatePhoneNr) {
            //jezeli w bazie istnieje klucz o wartosci idbazy danego numeru telefonu to zamien wartosc idbazy np. z 6 na 'event'
            if (array_key_exists($item->idbaza, $baza))
                $item->idbaza = $baza[$item->idbaza];
            //$found = false;
            foreach ($phoneNr as $key => $number) {
                //jezeli istnieje dany numer w tablicy numerow to go usun z tablicy
                if ($number == $item->telefon) {
                    unset($phoneNr[$key]);
                    /*if(!$found) {
                        $found = true;
                        unset($phoneNr[$key]);
                    }else{
                        array_push($duplicatePhoneNr, $number);
                        unset($phoneNr[$key]);
                    }*/
                }
            }
        });

        //dla pozostalych numerow ktore nie istnieja w bazie danych, nadaj nastepujace wartosci dla kodu pocztowage i idbazy
        foreach ($phoneNr as $number) {
            $records->push((object)['telefon' => intval($number), 'kodpocztowy' => 'Tego numeru nie ma w bazie', 'idbaza' => '']);
        }
        /*
        foreach ($duplicatePhoneNr as $number) {
            $records->push((object)['telefon' => intval($number), 'kodpocztowy' => 'Duplikat', 'idbaza' => '']);
        }*/

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
            $originalFileName = $request->file('import_file')->getClientOriginalName();
            $fileName = substr($originalFileName,0,strlen($originalFileName)-4);
            $path = $request->file('import_file')->getRealPath(); // for example: C:\xampp\tmp\php6E3A.tmp
            $this->createCSVWithUserData($path,$date,$time,$contents,$fileName);
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
    private function createCSVWithUserData($path, $date, $time, $text,$fileName) {
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
        $napis= $fileName;
        return Excel::create($napis, function ($excel) use ($completeLinesArray) {
            $excel->sheet('sheet1', function ($sheet) use ($completeLinesArray) {
                $sheet->fromArray($completeLinesArray, null, 'A1', false, false);
            });
        })->export('csv');
    }
}
