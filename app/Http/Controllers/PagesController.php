<?php

namespace App\Http\Controllers;
use App\Postcode;
use App\Record;
use App\Department;
use App\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use DB;
use Auth;

class PagesController extends Controller
{
    protected $delimiter  = ';';

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getIndex()
    {

        $user = Auth::user();//id uzytkownika
        if($user->rodzaj == "Badania")
            return redirect()->away('badania');
        else if($user->rodzaj == "Wysyłka")
            return redirect()->away('wysylka');
        else
            return redirect()->away('projekt');

    }
    public function getWoj()
    {
        return Region::select('woj')->get();
    }

    public function getWojByCity(Request $request)
    {
        $city = $request['city'];
        $idwoj= Postcode::select('idwoj')
            ->distinct()
            ->where('miasto', '=', $city)
            ->get();
        return $idwoj;
    }

    public function getChoice()
    {
        $user = Auth::user();//id uzytkownika
        if($user->rodzaj == "Badania")
            return redirect()->away('badania');
        else if($user->rodzaj == "Wysyłka")
            return redirect()->away('wysylka');
        else
            return redirect()->away('badania');
    }

    // wywołanie strony z badaniami
    public function getResearch()
    {
        $user = Auth::user();//id uzytkownika
        if($user->rodzaj == "Wysyłka")
            return redirect()->away('wysylka');
        //pobranie wszystkich miast z tabeli kod;
        $miasto = Postcode::select('miasto')->distinct()->get();
        //przekierowanie do widoku ze zmienną miasta;
        return view('pages.research')->with('miasta',$miasto);
    }
    public function getDepname()
    {
        $user = Auth::user();
        $nazwa= Department::select('name')
                ->where('id', '=', $user->dep_id)
                ->get();
        return $nazwa[0]['name'];

    }

    // zwracanie miast, po kodach lub nazwie miasta, tylko jeden return;
    public function searchFromData(Request $request)
    {

        //$request tablica z danymi przekazana przez ajax, input z miasta[2],kod od[0], kod do[1]
        $DaneWejsciowe = $request->dane;
        //typ projektu wysylka - badania
        $projekt =  $request->projekt;
        $wojewodztwo = $request->woj;
        // Gdy jest wporwadzony ciąg znaków
        if($DaneWejsciowe[3]!= null)
        {
            $kody = explode(",", $DaneWejsciowe[3]);
            $tab = array();
            foreach ($kody as $item)
            {
                $item = str_replace('-','',$item);
                $item = intval($item);
                array_push($tab,$item);
            }
            $tab = array_unique($tab);
        }

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
                        return Postcode::where('idkod', '>=', $kodod)
                            ->where('idkod', '<=', $koddo)
                            ->get();
                }
            }else
                return 0;
        }
        //Wyszukanie miast po nazwie miasta
        else if($DaneWejsciowe[0] == '' && $DaneWejsciowe[1] == '' && $DaneWejsciowe[2] != '' )
        {
            //Zmiana zanku w mieście
            $res = str_replace("|","/",$DaneWejsciowe[2]);
            return Postcode::where('miasto', '=', $res)
                ->where('idwoj', '=', $wojewodztwo)
                ->get();
        }
        //Wyszukanie miast po jednym kodzie pocztowym
        else if($DaneWejsciowe[0] != '' && $DaneWejsciowe[1] == '' && $DaneWejsciowe[2] == '' ) {

            $kodod = str_replace('-','',$DaneWejsciowe[0]);
            $kodod = intval($kodod);

                return Postcode::where('idkod', '=', $kodod)
                    ->get();

        }
        //Wyszukanie miast po kodzie i rejonce jednocześnie
        else if($DaneWejsciowe[0] != '' && $DaneWejsciowe[1] != '' && $DaneWejsciowe[2] != '' ) {
            //Analogia w operacjach
            $kodod = str_replace('-','',$DaneWejsciowe[0]);
            $kodod = intval($kodod);
            $koddo = str_replace('-','',$DaneWejsciowe[1]);
            $koddo = intval($koddo);
            $res = str_replace("|","/",$DaneWejsciowe[2]);

                return Postcode::where('idkod', '>=', $kodod)
                    ->where('idkod', '<=', $koddo)
                    ->where('miasto', '=', $res)
                    ->where('idwoj', '=', $wojewodztwo)
                    ->get();

        }//Gdy wyszukiwany jest po zakresie kodów pocztowych
        else if($DaneWejsciowe[0] == '' && $DaneWejsciowe[1] == '' && $DaneWejsciowe[2] == '' && $DaneWejsciowe[3]!= null )
        {
            return  Postcode::whereIn('idkod', $tab)
                    ->get();
        }
        return 0;
    }

    function setArray($tab)
    {
        $tablica = array();
        $tablica2 = array();
        foreach ($tab as $item)
        {
            array_push($tablica,$item);
        }

        foreach ($tablica as $item)
        {
            foreach ($item as $value)
            {
                array_push($tablica2,$value);
            }
        }
        return $tablica2;
    }


    // zworcenie nazwy miasta po kodzie pocztowym (Z prosby Ajax)
    public static function getCity(Request $request)
    {
        $res = $request['kod'];
        $kodod = str_replace('-','',$res);
        $kodod = intval($kodod);
        $nazwamiasta = Postcode::select('miasto')
                ->where('idkod', '=', $kodod)
                ->where('idkod', '=', $kodod)
                ->get();
        return $nazwamiasta;
    }
    //Generowanie pliku CSV na podstawie zebranych danych

    public function gererateCSV()
    {
        $data = date("Y-m-d H:i:s", strtotime('+1 hours'));
        $iledanych = session()->get('iledanych');
        $miasto = str_replace('-','/',session()->get('miasto'));

        if($iledanych[0] != 0 || $iledanych[1] != 0 || $iledanych[2] != 0 || $iledanych[3] != 0 || $iledanych[4] != 0){
            $napis = $miasto.'_Bis-'.$iledanych[0].'_zg-'.$iledanych[1].'_ev-'.$iledanych[3].'_r-'.$iledanych[2];
            $napis = $napis.'_'.$data;
        }else{
            $napis = $miasto.'_BisZG-'.$iledanych[5].'_zgZG-'.$iledanych[6].'_evZG-'.$iledanych[8].'_rZG-'.$iledanych[7];
            $napis = $napis.'_'.$data;
        }
//        if($iledanych[4] != 0 && ($iledanych[0] ==0 && $iledanych[1] == 0 && $iledanych[2] == 0 && $iledanych[3]==0))
//        {
//            $napis = $miasto.'_EXITO-'.$iledanych[4];
//            $napis = $napis.'_'.$data;
//        }
//        else if($iledanych[4] != 0 && ($iledanych[0] ==0 || $iledanych[1] == 0 || $iledanych[2] == 0 || $iledanych[3]==0))
//        {
//            $napis = $miasto.'_8-'.$iledanych[0].'_zg-'.$iledanych[1].'_ev-'.$iledanych[3].'_r-'.$iledanych[2].'_EXITO-'.$iledanych[4];
//            $napis = $napis.'_'.$data;
//        }
//        else{
//            $napis = $miasto.'_8-'.$iledanych[0].'_zg-'.$iledanych[1].'_ev-'.$iledanych[3].'_r-'.$iledanych[2];
//            $napis = $napis.'_'.$data;
//        }


        $dane =session()->get('tablicaDanych');
        $system =session()->get('system');

        // Tablica Naglówka
        $naglowek = array();
        //Na podstawie wybranego systemu strzoenie odpowiedniego nagłówka
        if($system == 0)
            $naglowek[] = array('Imie','Nazwisko','Ulica','Nr. Domu','Nr. Mieszkania','Miasto','Kod','Telefon');
        else
            $naglowek[] = array('Telefon','Imię','Nazwisko','Ulica','Numer domu','Kod pocztowy','Miasto','Miasto Poczty','Komentarz','Status','Ponowny Kontakt o:','Wpisz kod pocztowy lub miasto:');
        //Wpisanie danych do pliku
        foreach ($dane as $item)
        {
            if($system == 0)
                $naglowek[] = array($item['imie'],$item['nazwisko'],$item['ulica'],$item['nrdomu'],$item['nrmieszkania'],$item['miasto'],$item['idkod'],$item['telefon']);
            else
                $naglowek[] = array($item['telefon'],$item['imie'],$item['nazwisko'],$item['ulica'],$item['nrdomu'],$item['idkod'],$item['miasto']);
        }
        //Czyszczenie plików sesji
        self::unlucksession();
        //Zwrocenie Pliku do pobrania
        return Excel::create($napis, function($excel) use ($naglowek) {
            $excel->sheet('sheet1', function($sheet) use ($naglowek) {
                $sheet->fromArray($naglowek, null, 'A1', false, false);
            });
        })->export('csv');


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
    public function storageResearch(Request $request)
    {
        // czyszczenie plików sesji
        self::unlucksession();
        //Przypisanie zmiennych wysłanych przez ajax
        $system = $request['System'];
        $kody = $request['kody'];
        $bisnode = $request['bisnode'];
        $zgody = $request['zgody'];
        $reszta = $request['reszta'];
        $event = $request['event'];
        $exito = $request['exito'];

        $bisnodeZgody = $request['bisnodeZgody'];
        $zgodyZgody = $request['zgodyZgody'];
        $resztaZgody = $request['resztaZgody'];
        $eventZgody = $request['eventZgody'];
        $exitoZgody = $request['exitoZgody'];

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

        if($bisnode !=0)
        {   //Wywołanie metody setDane która pobiera wymagane dane w określonej ilości na podstawie określonych kodów
            self::setDate($kody,$bisnode,0);
        }
        if($zgody !=0)
        {
            self::setDate($kody,$zgody,1);
        }
        if($reszta !=0)
        {
            self::setDate($kody,$reszta,2);
        }
        if($event!=0)
        {
            self::setDate($kody,$event,3);
        }
        if($exito!=0)
        {
            self::setDate($kody,$exito,4);
        }


        if($bisnodeZgody!=0)
        {
            self::setDate($kody,$bisnodeZgody,5);
        }
        if($zgodyZgody!=0)
        {
            self::setDate($kody,$zgodyZgody,6);
        }
        if($resztaZgody!=0)
        {
            self::setDate($kody,$resztaZgody,7);
        }
        if($eventZgody!=0)
        {
            self::setDate($kody,$eventZgody,8);
        }
        if($exitoZgody!=0)
        {
            self::setDate($kody,$exitoZgody,9);
        }

        //Wywołanie funkcji UpdateData do zablokowanie pobieranych numerów
        self::updateData($bisnode,$zgody,$reszta,$event,$exito,
            $bisnodeZgody,$zgodyZgody,$resztaZgody,$eventZgody,$exitoZgody
            ,$miasto,$idwoj,$projekt);

    }
    //Zablokowanie numerów, dodanie wpisu w log_download, zmniejszenie licznika
    public function updateData($bisnode,$zgody,$reszta,$event,$exito,$bisnodeZgody,$zgodyZgody,$resztaZgody,$eventZgody,$exitoZgody,$miasto,$idwoj,$projekt)
    {
        $data = date("Y-m-d H:i:s", strtotime('+1 hours'));
        $databezgodiny = date("Y-m-d");
        // Insert do log_download z pustymi danymi.Rezerwacja id i pobranie go.
        $user = Auth::user();//id uzytkownika
        $idwoj =  DB::table('woj')->where('woj', $idwoj)->value('idwoj');
        $id = DB::table('log_download')->insertGetId(
            [
                'baza8' => $bisnode,
                'bazazg' => 0,
                'bazareszta' => 0,
                'bazaevent' => 0,
                'bazaexito' => 0,

                'baza8Zgody' => 0,
                'bazazgZgody' => 0,
                'bazaresztaZgody' => 0,
                'bazaeventZgody' => 0,
                'bazaexitoZgody' => 0,

                'id_user' => $user->id,
                'date' => $data,
                'miasto' => $miasto,
                'idwoj' => $idwoj,
                'status' => 0,
                'baza'=>session()->get('projekt')]
        );

            $daneDoZapisania = session()->get('tablicaDanych'); // wszystkie dane które checmy wykozystać
            $telefony = array();
            $kody = array();
            //zapisanie tablic telefonami i kodami;
            foreach ($daneDoZapisania as $item) {
                array_push($telefony,$item['telefon']);
                array_push($kody,$item['idkod']);
            }
            //Uzyskanie niepowtarzalnych kodów pocztowych
            $kodyBezDubli = array_unique($kody);
            //zamiana kluczy tablicy z warosciami, przypisanie wartosci na 0, Liczniki kodów
            $kodyBezDubli = array_flip($kodyBezDubli);
            $licznikBis = array_fill_keys(array_keys($kodyBezDubli),0);
            $licznikzg = array_fill_keys(array_keys($kodyBezDubli),0);
            $licznikresz = array_fill_keys(array_keys($kodyBezDubli),0);
            $licznikevent = array_fill_keys(array_keys($kodyBezDubli),0);
            $licznikexito = array_fill_keys(array_keys($kodyBezDubli),0);

            $licznikBisZgody = array_fill_keys(array_keys($kodyBezDubli),0);
            $licznikzgZgody = array_fill_keys(array_keys($kodyBezDubli),0);
            $licznikreszZgody = array_fill_keys(array_keys($kodyBezDubli),0);
            $licznikeventZgody = array_fill_keys(array_keys($kodyBezDubli),0);
            $licznikexitoZgody = array_fill_keys(array_keys($kodyBezDubli),0);

            //Liczniki ile faktycznie możemy pobrać(aby liczniki nie wyszły na -1 gdy pobranie odbędzie się w tym samym czasie)
            $poubdatebis =0;
            $poubdatezg =0;
            $poubdateev =0;
            $poubdateresz =0;
            $poubdateexito =0;

            $poubdatebisZgody =0;
            $poubdatezgZgody =0;
            $poubdateevZgody =0;
            $poubdatereszZgody =0;
            $poubdateexitoZgody =0;
            //Licznik ile rekordów przypada na dany kod
            foreach ($daneDoZapisania as $item) {
                if($item['idbaza'] == 8) {
                    $licznikBis[$item['idkod']]++;
                }else if($item['idbaza'] == 6)
                {
                    $licznikevent[$item['idkod']]++;
                }else if($item['idbaza'] == 5 ||$item['idbaza'] == 9 || $item['idbaza'] ==17)
                {
                    $licznikzg[$item['idkod']]++;
                }else if($item['idbaza'] == 19)
                {
                    $licznikexito[$item['idkod']]++;
                }
                else if($item['idbaza'] == 28)
                {
                    $licznikBisZgody[$item['idkod']]++;
                }else if($item['idbaza'] == 27)
                {
                    $licznikzgZgody[$item['idkod']]++;
                }else if($item['idbaza'] == 24)
                {
                    $licznikreszZgody[$item['idkod']]++;
                }else if($item['idbaza'] == 26)
                {
                    $licznikeventZgody[$item['idkod']]++;
                }else if($item['idbaza'] == 29)
                {
                    $licznikexitoZgody[$item['idkod']]++;
                }
                else
                {
                    $licznikresz[$item['idkod']]++;
                }
            }

        //Liczniki, zmniejszenie liczniki o odpowiednią wartość
        if($bisnode > 0)
            foreach($licznikBis as $key => $item)
            {
                $poubdatebis+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('bisnode_badania',$item);
                else
                    DB::table('kod')->where('idkod', $key)->decrement('bisnodeall', $item);

            }
        if($zgody > 0)
            foreach($licznikzg as $key => $item)
            {
                $poubdatezg+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('zgody_badania',$item);
                else
                    DB::table('kod')->where('idkod',$key)->decrement('zgodyall',$item);
            }
        if($reszta > 0)
            foreach($licznikresz as $key => $item)
            {
                $poubdateresz+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('reszta_badania',$item);
                else
                    DB::table('kod')->where('idkod',$key)->decrement('resztaall',$item);
            }

        if($event > 0)
            foreach($licznikevent as $key => $item)
            {
                $poubdateev+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('event_badania',$item);
                else
                    DB::table('kod')->where('idkod',$key)->decrement('eventall',$item);
            }

        if($exito > 0)
            foreach($licznikexito as $key => $item)
            {
                $poubdateexito+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('exito_badania',$item);
                else
                    DB::table('kod')->where('idkod',$key)->decrement('exitoall',$item);
            }


        if($bisnodeZgody > 0)
            foreach($licznikBisZgody as $key => $item)
            {
                $poubdatebisZgody+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('bisndeFromZgody_badania',$item);
                else
                    DB::table('kod')->where('idkod', $key)->decrement('bisndeFromZgody_all', $item);

            }

        if($zgodyZgody > 0)
            foreach($licznikzgZgody as $key => $item)
            {
                $poubdatezgZgody+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('zgodyFromZgody_badania',$item);
                else
                    DB::table('kod')->where('idkod',$key)->decrement('zgodyFromZgody_all',$item);
            }

        if($resztaZgody > 0)
            foreach($licznikreszZgody as $key => $item)
            {
                $poubdatereszZgody+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('resztaFromZgody_badania',$item);
                else
                    DB::table('kod')->where('idkod',$key)->decrement('resztaFromZgody_all',$item);
            }


        if($eventZgody > 0)
            foreach($licznikeventZgody as $key => $item)
            {
                $poubdateevZgody+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('eventFromZgody_badania',$item);
                else
                    DB::table('kod')->where('idkod',$key)->decrement('eventFromZgody_all',$item);
            }

        if($exitoZgody > 0)
            foreach($licznikexitoZgody as $key => $item)
            {
                $poubdateexitoZgody+=$item;
                if($projekt == "Badania")
                    DB::table('kod')->where('idkod',$key)->decrement('exitoFromZgody_badania',$item);
                else
                    DB::table('kod')->where('idkod',$key)->decrement('exitoFromZgody_all',$item);
            }

                //Tablica sesji ilości wykorzystana do generowania nazwy pliku csv
                $ilosc = array();
                array_push($ilosc,$poubdatebis);
                array_push($ilosc,$poubdatezg);
                array_push($ilosc,$poubdateresz);
                array_push($ilosc,$poubdateev);
                array_push($ilosc,$poubdateexito);

                array_push($ilosc,$poubdatebisZgody); //5
                array_push($ilosc,$poubdatezgZgody);
                array_push($ilosc,$poubdatereszZgody);
                array_push($ilosc,$poubdateevZgody);
                array_push($ilosc,$poubdateexitoZgody);

                session()->put('iledanych',$ilosc);
        //Insert już poprawnych danych
        DB::table('log_download')
            ->where('id',$id)
            ->update(
                [   'baza8'         => $poubdatebis,
                    'bazazg'        => $poubdatezg,
                    'bazareszta'    => $poubdateresz,
                    'bazaevent'     => $poubdateev,
                    'bazaexito'     => $poubdateexito,

                    'baza8Zgody' => $poubdatebisZgody,
                    'bazazgZgody' => $poubdatezgZgody,
                    'bazaresztaZgody' => $poubdatereszZgody,
                    'bazaeventZgody' => $poubdateevZgody,
                    'bazaexitoZgody' => $poubdateexitoZgody,

                    ]
            );

            //Blokowanie rekordów.

        if($projekt=="Badania")
        {
            $doUpdateProjekt = "badania";
            $doUpdateDataProjektu = "data";
        }else {
            $doUpdateProjekt = "wysylka";
            $doUpdateDataProjektu = "data_wysylka";
        }
            DB::table('rekordy')
                        ->whereIn('telefon',$telefony)
                        ->update([$doUpdateProjekt => $id,$doUpdateDataProjektu => $databezgodiny]);

    }
    //Pobranie wymaganej ilości danych z bazy, typ- rodzaj bazy 0-bisnode,1-zgody,2-reszta,3-event,4-exito
    public function setDate($kody,$ilosc,$typ)
    {
        $tablica = array();
        $blokada = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-7,date("Y")));

        if(session()->get('projekt') == "Badania")
        {
            $dataDoProjektu ="data";
            $Order = "badania";
        }else{
            $dataDoProjektu = "data_wysylka";
            $Order = "wysylka";
        }
        foreach ($kody as $item)
        {
            $kod = str_replace('-','',$item);
            $kod = intval($kod);

            $rekody = Record::select('imie', 'nazwisko', 'ulica', 'nrdomu', 'nrmieszkania', 'miasto', 'idkod', 'telefon','idbaza')
                    ->where('idkod', '=', $kod)
                    ->where('lock', '=', 0)
                    ->where($dataDoProjektu, '<', $blokada);
                    //->where('data_wysylka', '<', $blokada);

                if($typ == 0) {
                    $rekody = $rekody
                            ->where('idbaza', '=', 8);
                }else if($typ == 1)
                {
                    $rekody = $rekody
                            ->whereIn('idbaza',[5,9,17]);
                }else if($typ == 2)
                {
                    $rekody = $rekody
                            ->whereIn('idbaza',[1,2,3,4,7,10,11,12,13,14,15,16,18]);

                }else if($typ == 3)
                {
                    $rekody = $rekody
                            ->where('idbaza', '=', 6);
                }
                else if($typ == 4)
                {
                    $rekody = $rekody
                            ->where('idbaza', '=', 19);
                }
                else if($typ == 5)
                {   // zgody Bisnode
                    $rekody = $rekody
                        ->where('idbaza', '=', 28);
                }
                else if($typ == 6)
                {   // zgody nowe zgody
                    $rekody = $rekody
                        ->where('idbaza', '=', 27);
                }
                else if($typ == 7)
                { // zgody Reszta
                    $rekody = $rekody
                        ->where('idbaza', '=', 24);
                }
                else if($typ == 8)
                { // zgody event
                    $rekody = $rekody
                        ->where('idbaza', '=', 26);
                }
                else if($typ == 9)
                { // zgody exito
                    $rekody = $rekody
                        ->where('idbaza', '=', 29);
                }

                $rekody  = $rekody->orderBy($dataDoProjektu, 'asc')
                ->limit($ilosc)
                ->get();

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

    public function getShipment()
    {
        $user = Auth::user();//id uzytkownika
        if($user->rodzaj == "Badania")
            return redirect()->away('badania');
        $miasto = Postcode::select('miasto')->distinct()->get();
        return view('pages.shipment')->with('miasta',$miasto);
    }

}