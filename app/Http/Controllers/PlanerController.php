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

class PlanerController extends Controller
{
    //Wymagane zalogowanie do obsługi stony
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function  addroute()
    {
            return view('planer.addroute');
    }


}