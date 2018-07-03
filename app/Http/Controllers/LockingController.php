<?php

namespace App\Http\Controllers;

use App\LockHistory;
use App\Record;
use Illuminate\Http\Request;

use Yajra\DataTables\Facades\DataTables;

class LockingController extends Controller
{
    //

    public function lockGet()
    {
        return view('lock.lock');
    }

    public function lockPost(Request $request)
    {
        $response = '';
        $record = Record::where('telefon', '=', $request->telefon)->first();
        if ($record == null) {
            $record = new Record;
            $record->telefon = $request->telefon;
            $record->idbaza = 0;
            $record->lock = 1;
            $response = '.new';
        } else {
            if ($record->lock == 1) {
                return 'already locked';
            }
            $record->imie = "";
            $record->nazwisko = "";
            $record->ulica = "";
            $record->nrdomu = "";
            $record->nrmieszkania = "";
            $record->miasto = "";
            $record->idkod = 0;
            $record->idbaza = 30;
            $record->lock = 1;
        }

        $record->save();
        $lockHistory = new LockHistory;
        $lockHistory->id_baza = $record->idbaza;
        $lockHistory->telefon = $record->telefon;
        $lockHistory->save();

        return 'locked' . $response;
    }

    public function datatableLockAjax(Request $request)
    {
        $id_baza = $request->id_baza;
        $telefon = $request->telefon;
        $created_at = $request->created_at;

        $id_baza = $id_baza === null ? '%' : $id_baza;
        $telefon = $telefon === null ? '%' : $telefon;
        $created_at = $created_at === null ? '%' : $created_at;

        $lockHistory = LockHistory::where([
            ['id_baza', 'like', $id_baza],
            ['telefon', 'like', $telefon],
            ['created_at', 'like', $created_at.'%']
        ])->get();

        return datatables($lockHistory)->make(true);
    }
}
