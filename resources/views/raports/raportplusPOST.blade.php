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

    <form class="form-horizontal" method="post" action="raportplus">
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
            <tr style="background: yellow">
                <th>NR</th>
                <th>Imie</th>
                <th>Nazwisko</th>
                <th>Bisnode</th>
                <th>Bisnode Zgody</th>
                <th>Zgody</th>
                <th>Zgody Nowe</th>
                <th>Event</th>
                <th>Event Zgody</th>
                <th>Reszta</th>
                <th>Reszta Zgody</th>
                <th>Exito</th>
                <th>Exito Zgody</th>
                <th>Suma</th>
            </tr>
        </thead>
        <tbody>

        {{--Badania--}}
        <?php if(!empty($dane[0])) :?>
        <tr class="badania">
            <td colspan="3" style="text-align: center"><b>Ogół</b></td>
                <td><b>{{number_format($dane[0]['bisnode']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['bisnodeZgody']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['zgody']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['zgodyZgody']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['event']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['eventZgody']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['reszta']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['resztaZgody']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['exito']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($dane[0]['exitoZgody']/$dane[0]['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{$dane[0]['suma']}}</b></td>
        </tr>

        <?php foreach ($oddzialy as $item):?>

        <tr class="badania">
            <td colspan="3"><b> <?php echo $item['name']; ?> </b></td>
            <td><b>{{number_format($item['bisnode']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['bisnodeZgody']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['zgody']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['zgodyZgody']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['event']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['eventZgody']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['reszta']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['resztaZgody']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['exito']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($item['exitoZgody']/$item['suma'], 2, '', '')+0 }}%</b></td>
            <td><b> {{ $item['suma'] }} </b></td>
        </tr>
        <?php foreach ($employeeres as $value):?>

        @if( $item['id'] == $value['dep_id'] )
            <tr class="badania" >
                <td> {{ $i++ }}</td>
                <td> {{ $value['name'] }}</td>
                <td> {{ $value['last'] }}</td>
                <td><b>{{number_format($value['bisnode']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['bisnodeZgody']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['zgody']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['zgodyZgody']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['event']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['eventZgody']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['reszta']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['resztaZgody']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['exito']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td><b>{{number_format($value['exitoZgody']/$value['suma'], 2, '', '')+0 }}%</b></td>
                <td> {{ $value['suma'] }} </td>
            </tr>
            <?php foreach ($cityres as  $key):?>
            @if( $key['id_user'] == $value['id'] )
                <tr class="badania" >
                    <td> </td>
                    <td> {{ $key['woj'] }}</td>
                    <td> {{ $key['miasto'] }}</td>
                    <td> {{ $key['bisnode'] }} </td>
                    <td> {{ $key['bisnodeZgody'] }} </td>
                    <td> {{ $key['zgody'] }} </td>
                    <td> {{ $key['zgodyZgody'] }} </td>
                    <td> {{ $key['event'] }} </td>
                    <td> {{ $key['eventZgody'] }} </td>
                    <td> {{ $key['reszta'] }} </td>
                    <td> {{ $key['resztaZgody'] }} </td>
                    <td> {{ $key['exito'] }} </td>
                    <td> {{ $key['exitoZgody'] }} </td>
                    <td> {{ $key['suma'] }} </td>
                </tr>
            @endif
            <?php endforeach;?>
        @endif
        <?php endforeach;?>

        <?php endforeach;?>
<?php endif?>
        {{--WYSYLKA--}}
        <?php if(!empty($dane[1])) :?>
        <tr class="wysylka">
            <td colspan="3" style="text-align: center"><b>{{$dane[1]['baza']}}</b></td>
            <td><b>{{number_format($dane[1]['bisnode']/$dane[1]['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($dane[1]['zgody']/$dane[1]['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($dane[1]['event']/$dane[1]['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($dane[1]['reszta']/$dane[1]['suma'], 2, '', '')+0 }}%</b></td>
            <td><b>{{number_format($dane[1]['exito']/$dane[1]['suma'], 2, '', '')+0 }}%</b></td>

            <td><b>{{$dane[1]['suma']}}</b></td>
        </tr>

        <?php
        foreach ($zapytanie as $item):?>

        <tr class="wysylka">
            <td colspan="3"><b> <?php echo $item['name']; ?> </b></td>
            <td><b>{{number_format($item['bisnode']/$item['suma'], 2, '', '')+0 }}%</b> </td>
            <td><b> {{number_format($item['zgody']/$item['suma'], 2, '', '')+0 }}% </b></td>
            <td><b> {{number_format($item['event']/$item['suma'], 2, '', '')+0 }}% </b></td>
            <td><b> {{number_format($item['reszta']/$item['suma'], 2, '', '')+0 }}%</b> </td>
            <td><b> {{number_format($item['exito']/$item['suma'], 2, '', '')+0 }}%</b> </td>

            <td><b> {{ $item['suma'] }} </b></td>
        </tr>

        <?php
        foreach ($employeeship as $value):?>
        @if( $item['id'] == $value['dep_id'] )
            <tr class="wysylka">
                <td> {{ $i++ }}</td>
                <td> {{ $value['name'] }}</td>
                <td> {{ $value['last'] }}</td>
                <td> {{number_format($value['bisnode']/$value['suma'], 2, '', '')+0 }}% </td>
                <td> {{number_format($value['zgody']/$value['suma'], 2, '', '')+0 }}% </td>
                <td> {{number_format($value['event']/$value['suma'], 2, '', '')+0 }}% </td>
                <td> {{number_format($value['reszta']/$value['suma'], 2, '', '')+0 }}% </td>
                <td> {{number_format($value['exito']/$value['suma'], 2, '', '')+0 }}% </td>

                <td> {{ $value['suma'] }} </td>
            </tr>

            <?php foreach ($cityship as  $key):?>
            @if( $key['id_user'] == $value['id'] )
                <tr class="wysylka" >
                    <td> </td>
                    <td> {{ $key['woj'] }}</td>
                    <td> {{ $key['miasto'] }}</td>
                    <td> {{ $key['bisnode'] }} </td>
                    <td> {{ $key['zgody'] }} </td>
                    <td> {{ $key['event'] }} </td>
                    <td> {{ $key['reszta'] }} </td>
                    <td> {{ $key['exito'] }} </td>

                    <td> {{ $key['suma'] }} </td>
                </tr>
            @endif
            <?php endforeach;?>

        @endif
        <?php endforeach;?>
        <?php endforeach;?>
        <?php endif?>
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