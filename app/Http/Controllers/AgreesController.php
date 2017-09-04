<?php

namespace App\Http\Controllers;
use App\Postcode;
use App\Record;
use App\Department;
use App\RecordZG;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use DB;
use Auth;

class AgreesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function getNewBase()
    {
        $user = Auth::user();//id uzytkownika
        if($user->dep_id == 1) {
            $miasto = Postcode::select('miasto')->
            where('zgody_mail', '!=', '0')
                ->distinct()->get();
            //przekierowanie do widoku ze zmienną miasta;
            return view('agrees.newbase')->with('miasta', $miasto);
        }else if($user->rodzaj == "Badania")
            return redirect()->away('badania');
        else if($user->rodzaj == "Wysyłka")
            return redirect()->away('wysylka');
        else
            return redirect()->away('projekt');
    }

    public function getDepname()
    {
        $user = Auth::user();
        $nazwa= Department::select('name')->where('id', '=', $user->dep_id)->get();
        return $nazwa[0]['name'];

    }

    // zwracanie miast, po kodach lub nazwie miasta, tylko jeden return;
    public function searchFromData(Request $request)
    {
        //$request tablica z danymi przekazana przez ajax, input z miasta[2],kod od[0], kod do[1]
        $DaneWejsciowe = $request['dane'];
        //typ projektu wysylka - badania
        $projekt = $request['projekt'];
        //Wyszukiwanie po kodach pocztowych rejonka od-do
        if($DaneWejsciowe[0] != '' && $DaneWejsciowe[1] != '' && $DaneWejsciowe[2] == '' )
        {
            //Usuwanie - z kodów pocztowych i cast do int
            $kodod = str_replace('-','',$DaneWejsciowe[0]);
            $kodod = intval($kodod);
            $koddo = str_replace('-','',$DaneWejsciowe[1]);
            $koddo = intval($koddo);

            //rejonka nie większa niż 200
            if($kodod < $koddo && ($koddo <= $kodod+200))
            {   //wyszukanie miast przez select
                if($kodod !=0){
                    if($projekt == 'Badania')
                        return $rekordy = Postcode::select('idwoj','miasto','adres','kodpocztowy','zgody_mail')->where('idkod', '>=', $kodod)->where('idkod', '<=', $koddo)->get();
                }
            }
        }
        //Wyszukanie miast po nazwie miasta
        else if($DaneWejsciowe[0] == '' && $DaneWejsciowe[1] == '' && $DaneWejsciowe[2] != '' )
        {
            //Zmiana zanku w mieście
            $res = str_replace("|","/",$DaneWejsciowe[2]);
            if($projekt == 'Badania')
                return $rekordy = Postcode::select('idwoj','miasto','adres','kodpocztowy','zgody_mail','zgody')->where('miasto', '=', $res)->get();

        }
        //Wyszukanie miast po jednym kodzie pocztowym
        else if($DaneWejsciowe[0] != '' && $DaneWejsciowe[1] == '' && $DaneWejsciowe[2] == '' ) {

            $kodod = str_replace('-','',$DaneWejsciowe[0]);
            $kodod = intval($kodod);
            if($projekt == 'Badania')
                return $rekordy = Postcode::select('idwoj','miasto','adres','kodpocztowy','zgody_mail')->where('idkod', '=', $kodod)->get();

        }
        //Wyszukanie miast po kodzie i rejonce jednocześnie
        else if($DaneWejsciowe[0] != '' && $DaneWejsciowe[1] != '' && $DaneWejsciowe[2] != '' ) {
            //Analogia w operacjach
            $kodod = str_replace('-','',$DaneWejsciowe[0]);
            $kodod = intval($kodod);
            $koddo = str_replace('-','',$DaneWejsciowe[1]);
            $koddo = intval($koddo);
            $res = str_replace("|","/",$DaneWejsciowe[2]);
            if($projekt == 'Badania')
                return $rekordy = Postcode::select('idwoj','miasto','adres','kodpocztowy','zgody_mail')->where('idkod', '>=', $kodod)->where('idkod', '<=', $koddo)->where('miasto', '=', $res)->get();
        }

    }
    // zworcenie nazwy miasta po kodzie pocztowym (Z prosby Ajax)
    public static function getCity(Request $request)
    {
        $res = $request['kod'];
        $kodod = str_replace('-','',$res);
        $kodod = intval($kodod);
        $nazwamiasta = Postcode::select('miasto')->where('idkod', '=', $kodod)->where('idkod', '=', $kodod)->get();
        return $nazwamiasta;
    }
    //Generowanie pliku CSV na podstawie zebranych danych
    public function gererateCSVAgree()
    {
        $data = date("Y-m-d H:i:s");
        $iledanych = session()->get('iledanych');
        $miasto = str_replace('-','/',session()->get('miasto'));
        $napis = $miasto.'_8-'.$iledanych[0].'_zg-'.$iledanych[1].'_ev-'.$iledanych[3].'_r-'.$iledanych[2];
        $napis = $napis.'_'.$data;

        $dane =session()->get('tablicaDanych');
        $system =session()->get('system');

        // Tablica Naglówka
        $naglowek = array();
        //Na podstawie wybranego systemu strzoenie odpowiedniego nagłówka
        if($system == 0)
            $naglowek[] = array('Imie','Nazwisko','Ulica','Nr. Domu','Nr. Mieszkania','Miasto','Kod','Telefon');
        else
            $naglowek[] = array('Telefon','Imie','Nazwisko','Ulica','Nr. Domu','Kod Pocztowy','Miasto');
        //Wpisanie danych do pliku
        foreach ($dane as $item)
        {
            if($system == 0)
                $naglowek[] = array($item['imie'],$item['nazwisko'],$item['ulica'],$item['nrdomu'],$item['nrmieszkania'],$item['miasto'],$item['idkod'],$item['telefon']);
            else
                $naglowek[] = array($item['telefon'],$item['imie'],$item['nazwisko'],$item['ulica'],$item['nrdomu'],$item['idkod'],$item['miasto']);
        }
        //Zwrocenie Pliku do pobrania
        self::unlucksession();
        return Excel::create($napis, function($excel) use ($naglowek) {
            $excel->sheet('sheet1', function($sheet) use ($naglowek) {
                $sheet->fromArray($naglowek, null, 'A1', false, false);
            });
        })->download('csv');
        //Czyszczenie plików sesji


    }
    //Uwalnianie sesji
    public function unlucksession()
    {
        session()->forget('tablicaDanych');
        session()->forget('iledanych');
        session()->forget('projekt');
        session()->forget('miasto');
        session()->forget('system');
        session()->forget('projekt');
    }
    // cała magia updae insert i przekierowanie do generowania csv
    public function storageResearchAgree(Request $request)
    {
        // czyszczenie plików sesji
        self::unlucksession();
        //Przypisanie zmiennych wysłanych przez ajax
        $system = $request['System'];
        $kody = $request['kody'];
        $zgodyM = $request['zgody_mail'];
        $miasto = $request['miasto'];
        $idwoj = $request['idwoj'];
        $projekt = $request['projekt'];


        //pusta tablica to przechowywania rekordów do wygenerowanie przez csv
        $dane = array();
        //Ustanowienie plików sesji(można uzywać w różnych miejscach klasy
        session()->put('tablicaDanych',$dane);
        session()->put('system',$system);
        session()->put('miasto',$miasto);
        session()->put('projekt',$projekt);
        if($zgodyM !=0)
        {   //Wywołanie metody setDane która pobiera wymagane dane w określonej ilości na podstawie określonych kodów
            self::setDate($kody,$zgodyM,0);
        }
        //Wywołanie funkcji UpdateData do zablokowanie pobieranych numerów
        self::updateData();

    }
    //Zablokowanie numerów, dodanie wpisu w log_download, zmniejszenie licznika
    private function updateData()
    {
        $data = date("Y-m-d H:i:s");
        $databezgodiny = date("Y-m-d");
        // Insert do log_download z pustymi danymi.Rezerwacja id i pobranie go.

        $daneDoZapisania = session()->get('tablicaDanych'); // wszystkie dane które checmy wykozystać
        $telefony = array();
        $kody = array();
        //zapisanie tablic telefonami i kodami;
        foreach ($daneDoZapisania as $item) {
            array_push($telefony,$item['telefon']);
            array_push($kody,$item['idkod']);
        }

        $doUpdateDataProjektu = "date_download";

        DB::table('rekordyzg')
            ->whereIn('telefon',$telefony)
            ->update([$doUpdateDataProjektu => $databezgodiny]);

    }
    //Pobranie wymaganej ilości danych z bazy, typ- rodzaj bazy 0-bisnode,1-zgody,2-reszta,3-event
    private function setDate($kody,$ilosc,$typ)
    {
        $tablica = array();
        foreach ($kody as $item)
        {
            $kod = str_replace('-','',$item);
            $kod = intval($kod);
            {
                if($typ == 0) {
                    $rekody =
                        RecordZG::select('imie', 'nazwisko', 'ulica', 'nrdomu', 'nrmieszkania', 'miasto', 'idkod', 'telefon','idbaza')
                            ->where('idkod', '=', $kod)
                            ->where('lock', '=', 0)
                            ->where('idbaza', '=', 20)
                            ->orderBy('date_download', 'ASC')
                            ->take($ilosc)->get();
                }
            }
            if(count($rekody) < $ilosc)
            {
                foreach ($rekody as $item)
                {
                    array_push($tablica,$item);
                }
                $ilosc = $ilosc - count($rekody);
            }else
            {
                foreach ($rekody as $item)
                {
                    array_push($tablica,$item);
                }
                break;
            }
        }


        $tymczasowa = session()->get('tablicaDanych');
        foreach ($tablica as $item)
        {
            array_push($tymczasowa,$item);
        }
        session()->put('tablicaDanych',$tymczasowa);
    }

}