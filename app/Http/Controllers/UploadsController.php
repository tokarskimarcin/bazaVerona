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

class UploadsController extends Controller
{
    //Wymagane zalogowanie do obsługi ston
    public function __construct()
    {
        $this->middleware('auth');
    }
    //Strona do wgrywania danych "Zgody"
    public function  getUploadAgree()
    {
        if(Auth::user()->id == 1)
            return view('uploads.agree');
        else
            return redirect()->away('badania');
    }
    //Wyświetlenie strony do wgrania Eventu
    public function  getUploadEvent()
    {
        if(Auth::user()->id == 1)
            return view('uploads.event');
        else
            return redirect()->away('badania');
    }
    //Wyświetlenie strony do wgrania Bisnode
    public function  getUploadBisnode()
    {
        if(Auth::user()->id == 1)
            return view('uploads.bisnode');
        else
            return redirect()->away('badania');
    }
    //Wyświetlenie strony do wgrania Pomylek
    public function  getUploadMistake()
    {
        if(Auth::user()->id == 1)
            return view('uploads.mistake');
        else
            return redirect()->away('badania');
    }
    //Przetwarzanie danych po wybraniu pliku csv
    public  function showDate(Request $request)
    {
        //plik sesji do zapamiętania danych
        session()->forget('dane');
        $insert = array();
        //Wywyołanie metody wstawiającą dane do tablicy danych
        self::setArray($request,$insert);
        //Sprawdzenie czy tablica danych nie jest pusta, jesli tak wróć do strony wybierania liku
        if(!empty($insert) && isset($insert[0]['telefon']))
        {
            session()->push('dane',$insert);
            //Przekierowanie do strony wyświetlającej dane w tablicy
            return view('uploads.showDate')->with('dane',$insert)->with('naglowki',self::getHeaders($insert[0]))
                ->with('typ',$request->typ);
        }else{
            return back()->with('error','Please Check your file, Something is wrong there.');
        }
    }
    //metoda wyciągająca nagłówki z tablicy ("imie, nazwisko,telefon..")
    private function getHeaders($insert)
    {
        return array_keys($insert);
    }
    //Metoda ustawiająca tablicę danych z pliku csv
    private  function setArray(Request $request,&$insert)
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
                    if(array_key_exists('id', $value))
                    {
                        $tablica = array_merge($tablica,['id' => $value['id']]);
                    }
                    if(array_key_exists('imie', $value))
                    {
                        $tablica = array_merge($tablica,['imie' => $value['imie']]);
                    }
                    if(array_key_exists('nazwisko', $value))
                    {
                        $tablica = array_merge($tablica,['nazwisko' => $value['nazwisko']]);
                    }
                    if(array_key_exists('ulica', $value))
                    {
                        $tablica = array_merge($tablica,['ulica' => $value['ulica']]);
                    }
                    if(array_key_exists('nrdomu', $value))
                    {
                        $tablica = array_merge($tablica,['nrdomu' => $value['nrdomu']]);
                    }
                    if(array_key_exists('nrmieszkania', $value))
                    {
                        $tablica = array_merge($tablica,['nrmieszkania' => $value['nrmieszkania']]);
                    }
                    if(array_key_exists('idkod', $value))
                    {
                        $tablica = array_merge($tablica,['idkod' => intval($value['idkod'])]);
                    }
                    if(array_key_exists('idbaza', $value))
                    {
                        $tablica = array_merge($tablica,['idbaza' => intval($value['idbaza'])]);
                    }
                    if(array_key_exists('telefon', $value))
                    {
                        $tablica = array_merge($tablica,['telefon' => intval($value['telefon'])]);
                    }
                    if(array_key_exists('miasto', $value))
                    {
                        $tablica = array_merge($tablica,['miasto' => $value['miasto']]);
                    }
                    if(!empty($tablica))
                        $insert[] = $tablica;
                }
    }
    //Metoda upraszczająca klucze i wartości tablicy
    private function dedubArray($tab)
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
    //Metoda zapisująca dane z tablicy do bazy danych, AJAX
    public function save(Request $request)
    {

        $typ = $request->base;
        $dane = self::dedubArray(session()->get('dane'));
        $naglowki = self::getHeaders($dane[0]);

        $nowe = 0;
        $aktualizacja  = 0;

        foreach ($dane as $item)
        {
            unset($tablica);
            $tablica = array();
            //Czy numer jest poprawny
            if(strlen($item['telefon']) == 9) {
                //łączenie po kluczach jeśli dane mają przypisany nagłówek
                foreach($naglowki as $head)
                {
                    if($item[$head]!= null)
                        $tablica = array_merge($tablica,[$head => $item[$head]]);
                }
                //Dopisanie do tablicy odpowiedniej bazy "idBaza"
                if($typ == "zgody")
                    $tablica = self::idBaseAgree($tablica);
                else if($typ == "event")
                    $tablica = self::idBaseEvent($tablica);
                else if($typ == "bisnode")
                    $tablica = self::idBaseBisnode($tablica);

                // Czy numer jest w bazie, aby uniknąć dubli,
                // do count aby sprawdzić czy jest w bazie  1- jest jeden, 2> jest więcej niż jeden rekord
                // 0 brak numeru w bazie.
                $czyJestWBazie = count(self::countAgree($item['telefon']));

                    if ($czyJestWBazie == 1) {   // rekord jest w bazie
                        //Sprawdzenie czy dany rekord można dodać do bazy = 1
                        if($typ == "event")
                            $czyMoznaDodac = count(self::countEvent($item['telefon']));
                        else if($typ == "bisnode")
                            $czyMoznaDodac = count(self::countBisnode($item['telefon']));
                        else    // zgody i pomylki zawsze mozna = 1
                            $czyMoznaDodac = 1;

                        if($czyMoznaDodac == 1)
                        {
                            $kopia =  DB::table('rekordy')
                                ->where('telefon', '=', $item['telefon'])
                                ->where('lock','=',0)->get();

                            if($kopia->isNotEmpty()) // jeśli można dodać numer
                            {
                                // wyłuskanie danych z numeru przed aktualizacją
                                $kopia = $this::dedubArray($kopia);
                                // kopia id rekordu
                                $telefon_id = $kopia[0];
                                // baza rekordu
                                $telefon_baza = $kopia[9];
                                // wrzucenie infromacji o rekordach
                                DB::table('old_new_base')->insert(['id_record' => $telefon_id,'old_base' => $telefon_baza]);
                                // Dodanie rekordu do bazy

                                if($typ == "zgody"){
                                    if($telefon_baza == 8){
                                        $idbaza = 28;
                                    }else if($telefon_baza == 6){
                                        $idbaza = 26;
                                    }else if($telefon_baza == 19){
                                        $idbaza = 29;
                                    }else if($telefon_baza == 5 || $telefon_baza == 9 || $telefon_baza == 17){
                                        $idbaza = 27;
                                    }else if($telefon_baza == 28 || $telefon_baza == 26
                                        || $telefon_baza == 29 || $telefon_baza == 27
                                        || $telefon_baza == 24){
                                        $idbaza = $telefon_baza;
                                    }else{
                                        $idbaza = 24;
                                    }
                                    dd($idbaza);
                                    DB::table('rekordy')
                                        ->where('telefon', '=', $item['telefon'])
                                        ->where('lock','=',0)
                                        ->update('idkod','=',$idbaza);
                                }else
                                {
                                    DB::table('rekordy')
                                        ->where('telefon', '=', $item['telefon'])
                                        ->where('lock','=',0)
                                        ->update($tablica);
                                }
                                $aktualizacja++;
                            }
                        }
                    }else if ($czyJestWBazie == 0) { // nowy rekord w bazie, nie dodajemy nowych zgód
                        if($typ != "zgody")
                        {
                            DB::table('rekordy')
                                ->insert($tablica);
                            $nowe++;
                        }
                    }
                }
        }
        $wynik= array($aktualizacja,$nowe);
        return $wynik;

    }
    //Metoda sprawdzająca czy numer jest w bazie danych
    private function countAgree($telefon)
    {
        $wynik  = DB::table('rekordy')
            ->selectRaw('1')
            ->where('telefon', '=', $telefon)
            ->get();
        return $wynik;
    }
    //Metoda sprawdzająca czy dany numer można przypisac jako Event
    private function countEvent($telefon)
    {
        $czyJestWBazie = count(self::countAgree($telefon));

        $wynik  = DB::table('rekordy')
            ->selectRaw('1')
            ->where('telefon', '=', $telefon)
            ->where('idbaza', '!=', 8)
            ->where('idbaza', '!=', 17)
            ->where('idbaza', '!=', 9)
            ->where('idbaza', '!=', 5)
            ->where('idbaza', '!=', 30)
            ->get();
        return $wynik;
    }
    //Metoda sprawdzająca czy dany numer można przypisac jako Bisnode 8
    private function countBisnode($telefon)
    {
        $wynik  = DB::table('rekordy')
            ->selectRaw('1')
            ->where('telefon', '=', $telefon)
            ->where('idbaza', '!=', 17)
            ->where('idbaza', '!=', 9)
            ->where('idbaza', '!=', 5)
            ->where('idbaza', '!=', 6)
            ->where('idbaza', '!=', 30)
            ->get();
        return $wynik;
    }
    //Metoda zwracająca odpowiedni numer bazy
    private  function idBaseAgree($tablica)
    {
        return array_merge($tablica,['idbaza' => '17']);
    }
    //Metoda zwracająca odpowiedni numer bazy
    private  function idBaseEvent($tablica)
    {
        return array_merge($tablica,['idbaza' => '6']);
    }
    //Metoda zwracająca odpowiedni numer bazy
    private  function idBaseBisnode($tablica)
    {
        return array_merge($tablica,['idbaza' => '8']);
    }

}