<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
class unlockController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    //'status' => 0,
    public function getRecords()
    {

        $user = Auth::user();
        if ($user->id == 1 || $user->id == 105 || $user->id == 102 || $user->id == 109) {

            $date = date('Y-m-d', strtotime('-7 days', time()));
            $lista = DB::table('log_download')
                ->selectRaw('log_download.id,status,users_t.name,users_t.last,baza8 as bisnode,bazazg as zgody,bazareszta as reszta,
            bazaevent as event,date as data,miasto,woj.woj as wojewodztwo,baza')
                ->join('users_t', 'id_user', 'users_t.id')
                ->join('woj', 'log_download.idwoj', 'woj.idwoj')
                ->where('date', '>=', $date . '%')
                ->where('status', '=', 0)
                ->orderBy('log_download.id', 'desc')
                ->get();
            $lista = json_decode(json_encode((array)$lista), true);
            $lista = $this->setArray($lista);
            return view('unlock.list')->with('lista', $lista);
        }else
        {
            return redirect()->away('badania');
        }
    }

    public function showDate(Request $request)
    {
        //plik sesji do zapamiętania danych
        session()->forget('dane');
        $insert = array();
        //Wywyołanie metody wstawiającą dane do tablicy danych
        self::setArrayToSave($request,$insert);
        //Sprawdzenie czy tablica danych nie jest pusta, jesli tak wróć do strony wybierania liku
        if(!empty($insert) && isset($insert[0]['telefon']))
        {
            session()->push('dane',$insert);
            //Przekierowanie do strony wyświetlającej dane w tablicy
            return view('unlock.csvBlockData')->with('dane',$insert)->with('naglowki',self::getHeaders($insert[0]))
                ->with('typ',$request->typ);
        }else{
            return back()->with('error','Problem z plikiem, sprawdź czy wystepuje odpowiedni nagłówek telefon oraz czy nie ma spacji w telefonach.');
        }
    }

    public function LockData(Request $request)
    {
        $typ = $request->base;
        $dane = session()->get('dane');
        $date= date('Y-m-d');
        unset($tablica);
        $numery= array();
        foreach ($dane as $item) {
            foreach ($item as $value)
            array_push($numery,$value['telefon']);
        }
        // kody pocztowe do licznika
       $zipcode = self::setZipCode($numery);


        if ($typ == "Badania") {
                DB::table('rekordy')
                    ->whereIn('telefon', $numery)
                    ->update(['data' => $date]);
            $rodzaj = 'data';
            $bisnode ='bisnode_badania';
            $zgody = 'zgody_badania';
            $reszta = 'reszta_badania';
            $event = 'event_badania';
        }else if($typ == "Wysyłka")
        {
            DB::table('rekordy')
                ->whereIn('telefon', $numery)
                ->update(['data_wysylka' => $date]);
            $rodzaj = 'data_wysylka';
            $bisnode ='bisnodeall';
            $zgody = 'zgodyall';
            $reszta = 'resztaall';
            $event = 'eventall';
        }

        foreach ($zipcode as $item)
        {
            self::zliczenieBisnode($item['idkod'],$rodzaj,$bisnode);
            self::zliczenieZgod($item['idkod'],$rodzaj,$zgody);
            self::zliczenieEvent($item['idkod'],$rodzaj,$event);
            self::zliczenieReszta($item['idkod'],$rodzaj,$reszta);
        }
        return "OK";
    }

    public function setZipCode($numery)
    {
        $codeList = DB::table('rekordy')
            ->select(DB::raw('idkod'))
            ->whereIn('telefon', $numery)
            ->groupBy('idkod')
            ->get();
        $codeList = json_decode(json_encode((array) $codeList), true);
        $codeList = $this->setArray($codeList);
        return $codeList;
    }


    public function save(Request $request)
    {
        session()->forget('kody');
        $typ = $request->base;
        $dane = self::dedubArray(session()->get('dane'));
        $naglowki = self::getHeaders($dane[0]);
        $date= date('Y-m-d');
        $nowe = 0;
        $aktualizacja  = 0;

        foreach ($dane as $item)
        {
            unset($tablica);
            $tablica = array();
            //Czy numer jest poprawny
            if(strlen($item['telefon']) == 9) {
                //łączenie po kluczach jeśli dane mają przypisany nagłówek
                foreach ($naglowki as $head) {
                    if ($item[$head] != null)
                        $tablica = array_merge($tablica, [$head => $item[$head]]);
                }
                //Dopisanie do tablicy odpowiedniej bazy "idBaza"
                if ($typ == "Badania") {
                    $tablica = self::addDate($tablica);
                   // array_merge($tablica,['data' => $date]);

                } else if ($typ == "Wysyłka")
                {
                    $tablica = self::addDate($tablica);
                    //array_merge($tablica,['data_wysylka' => $date]);
                }

//
//                // Czy numer jest w bazie, aby uniknąć dubli,
//                // do count aby sprawdzić czy jest w bazie  1- jest jeden, 2> jest więcej niż jeden rekord
//                // 0 brak numeru w bazie.
//                $czyJestWBazie = count(self::countAgree($item['telefon']));
//
//                if ($czyJestWBazie == 1) {   // rekord jest w bazie
//                    //Sprawdzenie czy dany rekord można dodać do bazy = 1
//                    if($typ == "event")
//                        $czyMoznaDodac = count(self::countEvent($item['telefon']));
//                    else if($typ == "bisnode")
//                        $czyMoznaDodac = count(self::countBisnode($item['telefon']));
//                    else    // zgody i pomylki zawsze mozna = 1
//                        $czyMoznaDodac = 1;
//
//                    if($czyMoznaDodac == 1)
//                    {   // Dodanie rekordu do bazy
//                        DB::table('rekordy')
//                            ->where('telefon', '=', $item['telefon'])
//                            ->update($tablica);
//                        $aktualizacja++;
//                    }
//                }else if ($czyJestWBazie == 0) { // nowy rekord w bazie
//                    DB::table('rekordy')
//                        ->insert($tablica);
//                    $nowe++;
//                }
            }
        }
        $wynik= array($aktualizacja,$nowe);
        return $tablica;

    }


    private function getHeaders($insert)
    {
        return array_keys($insert);
    }

    private  function setArrayToSave(Request $request,&$insert)
    {
        session()->forget('dane');
        ini_set("memory_limit","7G");
        ini_set('max_execution_time', '0');
        ini_set('max_input_time', '0');
        set_time_limit(0);
        ignore_user_abort(true);
        $path = $request->file('import_file')->getRealPath();
        $data = Excel::load($path, function($reader) {})->get();

        foreach ($data->toArray() as $key => $value) {
            unset($tablica);
            $tablica = array();
            if(array_key_exists('telefon', $value))
            {
                $tablica = array_merge($tablica,['telefon' => intval($value['telefon'])]);
            }
            if(!empty($tablica))
                $insert[] = $tablica;
        }
    }


    public function getRecordsPost(Request $request)
    {

        $miasto = $request->input('miasto');
        $date= date('Y-m-d', strtotime('-7 days', time()));

        $lista = DB::table('log_download')
            ->selectRaw('log_download.id,status,users_t.name,users_t.last,baza8 as bisnode,bazazg as zgody,bazareszta as reszta,
            bazaevent as event,date as data,miasto,woj.woj as wojewodztwo,baza')
            ->join('users_t', 'id_user', 'users_t.id')
            ->join('woj', 'log_download.idwoj', 'woj.idwoj')
            ->where('status','=',0)
            ->where('date','>=',$date.'%')
            ->where('miasto','like',$miasto.'%')
            ->orderBy('log_download.id', 'desc')
            ->get();
        $lista = json_decode(json_encode((array) $lista), true);
        $lista = $this->setArray($lista);
        return view('unlock.list')->with('lista',$lista);
    }

    public function UnlockRecordsPost(Request $request)
    {
        $miasto = $request->input('kod');
        $date= date('Y-m-d', strtotime('-10 days', time()));
        if ($miasto[1] == 'Wysylka') {

            $lista = DB::table('rekordy')
                ->select('idkod')->distinct()->where('wysylka', '=', $miasto[0])
                ->get();
            $rodzaj = 'data_wysylka';
            $bisnode ='bisnodeall';
            $zgody = 'zgodyall';
            $reszta = 'resztaall';
            $event = 'eventall';

            DB::table('rekordy')
                ->where('wysylka',$miasto[0])
                ->update(['data_wysylka' => $date]);

        }else
        {
            $lista = DB::table('rekordy')
                ->select('idkod')->distinct()->where('badania', '=', $miasto[0])
                ->get();
            $rodzaj = 'data';
            $bisnode ='bisnode_badania';
            $zgody = 'zgody_badania';
            $reszta = 'reszta_badania';
            $event = 'event_badania';

            DB::table('rekordy')
                ->where('badania',$miasto[0])
                ->update(['data' => $date]);
        }

        DB::table('log_download')
            ->where('log_download.id',$miasto[0])
            ->update(['status' => 1]);

        $lista = json_decode(json_encode((array) $lista), true);
        $lista = $this->setArray($lista);
        $tablica = array();
        foreach ($lista as $item)
        {
            self::zliczenieBisnode($item['idkod'],$rodzaj,$bisnode);
            self::zliczenieZgod($item['idkod'],$rodzaj,$zgody);
            self::zliczenieEvent($item['idkod'],$rodzaj,$event);
            self::zliczenieReszta($item['idkod'],$rodzaj,$reszta);
        }
        return $tablica;
    }

    public function zliczenieBisnode($idkod,$baza,$typ)
    {
        $date= date('Y-m-d', strtotime('-7 days', time()));
        $lista = DB::table('rekordy')
            ->selectRaw('count(idkod)')
            ->where('idkod','=',$idkod)
            ->where($baza,'<',$date)
            ->where('idbaza','=',8)
            ->where('lock','=',0)
            ->get();
        $lista = json_decode(json_encode((array) $lista), true);
        $lista = $this->setArray($lista);
        $wynik = $lista[0]['count(idkod)'];

        DB::table('kod')
            ->where('kod.idkod',$idkod)
            ->update([$typ => $wynik]);

        return $wynik;
    }
    public function zliczenieZgod($idkod,$baza,$typ)
    {
        $date= date('Y-m-d', strtotime('-7 days', time()));
        $lista = DB::table('rekordy')
            ->selectRaw('count(idkod)')
            ->where('idkod','=',$idkod)
            ->where($baza,'<',$date)
            ->where(function ($querry)
            {
                $querry->orWhere('idbaza', '=', 5)
                    ->orWhere('idbaza', '=', 9)
                    ->orWhere('idbaza', '=', 17);
            })
            ->where('lock','=',0)
            ->get();
        $lista = json_decode(json_encode((array) $lista), true);
        $lista = $this->setArray($lista);
        $wynik = $lista[0]['count(idkod)'];

        DB::table('kod')
            ->where('kod.idkod',$idkod)
            ->update([$typ => $wynik]);

        return $wynik;
    }
    public function zliczenieEvent($idkod,$baza,$typ)
    {
        $date= date('Y-m-d', strtotime('-7 days', time()));
        $lista = DB::table('rekordy')
            ->selectRaw('count(idkod)')
            ->where('idkod','=',$idkod)
            ->where($baza,'<',$date)
            ->where('idbaza','=',6)
            ->where('lock','=',0)
            ->get();
        $lista = json_decode(json_encode((array) $lista), true);
        $lista = $this->setArray($lista);
        $wynik = $lista[0]['count(idkod)'];

        DB::table('kod')
            ->where('kod.idkod',$idkod)
            ->update([$typ => $wynik]);

        return $wynik;
    }
    public function zliczenieReszta($idkod,$baza,$typ)
    {
        $date= date('Y-m-d', strtotime('-7 days', time()));
        $lista = DB::table('rekordy')
            ->selectRaw('count(idkod)')
            ->where('idkod','=',$idkod)
            ->where($baza,'<',$date)
            ->where(function ($querry)
            {
                $querry->orWhere('idbaza', '=', 1)
                    ->orWhere('idbaza', '=', 2)
                    ->orWhere('idbaza', '=', 3)
                    ->orWhere('idbaza', '=', 4)
                    ->orWhere('idbaza', '=', 7)
                    ->orWhere('idbaza', '=', 10)
                    ->orWhere('idbaza', '=', 11)
                    ->orWhere('idbaza', '=', 12)
                    ->orWhere('idbaza', '=', 13)
                    ->orWhere('idbaza', '=', 14)
                    ->orWhere('idbaza', '=', 15)
                    ->orWhere('idbaza', '=', 16)
                    ->orWhere('idbaza', '=', 18);
            })
            ->where('lock','=',0)
            ->get();
        $lista = json_decode(json_encode((array) $lista), true);
        $lista = $this->setArray($lista);
        $wynik = $lista[0]['count(idkod)'];

        DB::table('kod')
            ->where('kod.idkod',$idkod)
            ->update([$typ => $wynik]);

        return $wynik;
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


}
