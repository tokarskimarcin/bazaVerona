@extends('main')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
    <link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />

    <!--Font Awesome (added because you use icons in your prepend/append)-->
    <link rel="stylesheet" href="https://formden.com/static/cdn/font-awesome/4.4.0/css/font-awesome.min.css" />

    <!-- Inline CSS based on choices in "Settings" tab -->
    <style>.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form{font-family: Arial, Helvetica, sans-serif; color: black}.bootstrap-iso form button, .bootstrap-iso form button:hover{color: white !important;} .asteriskField{color: red;}</style>

    <style>
    #pole1,#pole2,#pole3
    {
        height: 68px;
    }
        .badania
        {
            background-color: #d8f5fb;
        }
        .wysylka
        {
            background-color: antiquewhite;
        }
        .suma{
            background-color: darkorange;
        }

    </style>

@endsection
@section('content')

    <?php
        $i = 1;
    ?>
    <h2>Raport wykorzystania bazy z
    <?php if(count($dataraportu)>1)
        echo 'dni: '.$dataraportu[0].' - '.$dataraportu[1];
        else
        {
            echo 'dnia: '.$dataraportu[0];
        }
        ?>
    </h2>
    <p>Uwaga: Raport obejmuje osoby, korzystające z nowego systemu od dnia: 05.04.2017 </p>

    <form class="form-horizontal" method="post" action="raport">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
<div class="pole">
        <div id="pole1">
            <label class="control-label col-sm-2 requiredField" for="date">
                Data raportu (pojedyńczy dzień)
                <span class="asteriskField">
                *
               </span>
            </label>
            <div class="col-sm-10">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar">
                        </i>
                    </div>
                    <input class="form-control" id="date" name="datejeden" placeholder="YYYY/MM/DD" type="text"/>
                </div>
            </div>
        </div>

        <div id="pole2">
            <label class="control-label col-sm-2 requiredField" for="date">
                Data raportu od
                <span class="asteriskField">
                *
               </span>
            </label>
            <div class="col-sm-10">
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar">
                        </i>
                    </div>
                    <input class="form-control" id="date" name="dateod" placeholder="YYYY/MM/DD" type="text"/>
                </div>
            </div>
        </div>

    <div id="pole3">
        <label class="control-label col-sm-2 requiredField" for="date">
            Data raportu do
            <span class="asteriskField">
                *
               </span>
        </label>
        <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fa fa-calendar">
                    </i>
                </div>
                <input class="form-control" id="date" name="datedo" placeholder="YYYY/MM/DD" type="text"/>
            </div>
        </div>
    </div>
</div>



    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <button class="btn btn-primary " name="submit" type="submit">
                Generuj
            </button>
        </div>
    </div>
    </form>

    <table class="table table-bordered">
        <thead>
        <tr  style="background: yellow">
            <th>NR</th>
            <th>Imie</th>
            <th>Nazwisko</th>
            <th>Bisnode</th>
            <th>Bisnode Zgody</th>
            <th>Bisnode Suma</th>
            <th>Zgody</th>
            <th>Zgody Nowe</th>
            <th>Zgody Suma</th>
            <th>Event</th>
            <th>Event Zgody</th>
            <th>Event Suma</th>
            <th>Reszta</th>
            <th>Reszta Zgody</th>
            <th>Reszta Suma</th>
            <th>Suma</th>
        </tr>
        </thead>
        <tbody>
        @php
            $badania = $dane->where('baza','like','Badania')->first();
        @endphp
        @if(!empty($dane))
        <tr class="badania">
            <td colspan="3" style="text-align: center"><b>Ogół</b></td>
                @if($badania->suma == 0)
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0</b></td>
                @else
                    <td><b>{{number_format($badania->bisnode/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($badania->bisnodeZgody/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td class="suma"><b>{{number_format(($badania->bisnodeZgody+$badania->bisnode)/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($badania->zgody/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($badania->zgodyZgody/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($badania->zgody+$badania->zgodyZgody)/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($badania->event/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($badania->eventZgody/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($badania->event+$badania->eventZgody)/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($badania->reszta/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($badania->resztaZgody/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($badania->reszta+$badania->resztaZgody)/$badania->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{$badania->suma}}</b></td>
            @endif
        </tr>

        @foreach ($oddzialy as $item)
                    <tr class="badania">
                        <td colspan="3"><b> <?php echo $item->name; ?> </b></td>
                        <td><b>{{number_format($item->bisnode/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td><b>{{number_format($item->bisnodeZgody/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td  class="suma"><b>{{number_format(($item->bisnode+$item->bisnodeZgody)/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td><b>{{number_format($item->zgody/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td><b>{{number_format($item->zgodyZgody/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td  class="suma"><b>{{number_format(($item->zgody+$item->zgodyZgody)/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td><b>{{number_format($item->event/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td><b>{{number_format($item->eventZgody/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td  class="suma"><b>{{number_format(($item->event+$item->eventZgody)/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td><b>{{number_format($item->reszta/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td><b>{{number_format($item->resztaZgody/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td  class="suma"><b>{{number_format(($item->reszta+$item->resztaZgody)/$item->suma, 2, '', '')+0 }}%</b></td>
                        <td><b> {{ $item->suma }} </b></td>
                    </tr>
                        @foreach ($employeeres as $value)
                            @if( $item->id == $value->dep_id )
                                <tr class="badania" >
                                    <td> {{ $i++ }}</td>
                                    <td> {{ $value->name }}</td>
                                    <td> {{ $value->last }}</td>
                                    <td><b>{{number_format($value->bisnode/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td><b>{{number_format($value->bisnodeZgody/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td  class="suma"><b>{{number_format(($value->bisnode+$value->bisnodeZgody)/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td><b>{{number_format($value->zgody/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td><b>{{number_format($value->zgodyZgody/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td  class="suma"><b>{{number_format(($value->zgody+$value->zgodyZgody)/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td><b>{{number_format($value->event/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td><b>{{number_format($value->eventZgody/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td  class="suma"><b>{{number_format(($value->event+$value->eventZgody)/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td><b>{{number_format($value->reszta/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td><b>{{number_format($value->resztaZgody/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td  class="suma"><b>{{number_format(($value->reszta+$value->resztaZgody)/$value->suma, 2, '', '')+0 }}%</b></td>
                                    <td> {{ $value->suma }} </td>
                                </tr>
                            @endif
                       @endforeach
            @endforeach

        @php
            $wysylka = $dane->where('baza','like','Wysylka')->first();
        @endphp

            <tr class="wysylka">
                <td colspan="3" style="text-align: center"><b>Ogół</b></td>
                @if($wysylka->suma == 0)
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0 %</b></td>
                    <td><b>0</b></td>
                @else
                    <td><b>{{number_format($wysylka->bisnode/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($wysylka->bisnodeZgody/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td class="suma"><b>{{number_format(($wysylka->bisnodeZgody+$wysylka->bisnode)/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($wysylka->zgody/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($wysylka->zgodyZgody/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($wysylka->zgody+$wysylka->zgodyZgody)/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($wysylka->event/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($wysylka->eventZgody/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($wysylka->event+$wysylka->eventZgody)/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($wysylka->reszta/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($wysylka->resztaZgody/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($wysylka->reszta+$wysylka->resztaZgody)/$wysylka->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{$wysylka->suma}}</b></td>
                @endif
            </tr>

            @foreach ($oddzialyWys as $item)
                <tr class="wysylka">
                    <td colspan="3"><b> <?php echo $item->name; ?> </b></td>
                    <td><b>{{number_format($item->bisnode/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($item->bisnodeZgody/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($item->bisnode+$item->bisnodeZgody)/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($item->zgody/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($item->zgodyZgody/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($item->zgody+$item->zgodyZgody)/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($item->event/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($item->eventZgody/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($item->event+$item->eventZgody)/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($item->reszta/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td><b>{{number_format($item->resztaZgody/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td  class="suma"><b>{{number_format(($item->reszta+$item->resztaZgody)/$item->suma, 2, '', '')+0 }}%</b></td>
                    <td><b> {{ $item->suma }} </b></td>
                </tr>
                @foreach ($employeeship as $value)
                    @if( $item->id == $value->dep_id )
                        <tr class="wysylka" >
                            <td> {{ $i++ }}</td>
                            <td> {{ $value->name }}</td>
                            <td> {{ $value->last }}</td>
                            <td><b>{{number_format($value->bisnode/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td><b>{{number_format($value->bisnodeZgody/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td  class="suma"><b>{{number_format(($value->bisnode+$value->bisnodeZgody)/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td><b>{{number_format($value->zgody/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td><b>{{number_format($value->zgodyZgody/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td  class="suma"><b>{{number_format(($value->zgody+$value->zgodyZgody)/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td><b>{{number_format($value->event/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td><b>{{number_format($value->eventZgody/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td  class="suma"><b>{{number_format(($value->event+$value->eventZgody)/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td><b>{{number_format($value->reszta/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td><b>{{number_format($value->resztaZgody/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td  class="suma"><b>{{number_format(($value->reszta+$value->resztaZgody)/$value->suma, 2, '', '')+0 }}%</b></td>
                            <td> {{ $value->suma }} </td>
                        </tr>
                    @endif
                @endforeach
            @endforeach
@endif
        </tbody>
    </table>
@endsection
@section('script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>


    <script>
        $(document).ready(function(){
            var date_input=$('input[name="datejeden"]'); //our date input has the name "date"
            var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
            date_input.datepicker({
                format: 'yyyy-mm-dd',
                container: container,
                todayHighlight: true,
                autoclose: true,
                startDate: "2017-04-05",
                endDate: 'today',
            })

            var date_input=$('input[name="dateod"]'); //our date input has the name "date"
            var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
            date_input.datepicker({
                format: 'yyyy-mm-dd',
                container: container,
                todayHighlight: true,
                autoclose: true,
                startDate: "2017-04-05",
                endDate: 'today',
            })

            var date_input=$('input[name="datedo"]'); //our date input has the name "date"
            var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
            date_input.datepicker({
                format: 'yyyy-mm-dd',
                container: container,
                todayHighlight: true,
                autoclose: true,
                startDate: "2017-04-05",
                endDate: 'today',
            })
        })


    </script>

@endsection