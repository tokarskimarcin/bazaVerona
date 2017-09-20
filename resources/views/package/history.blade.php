@extends('main')
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>

        #loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            display: none;
        }

        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Add animation to "page content" */
        .animate-bottom {
            position: relative;
            -webkit-animation-name: animatebottom;
            -webkit-animation-duration: 1s;
            animation-name: animatebottom;
            animation-duration: 1s
        }

        @-webkit-keyframes animatebottom {
            from { bottom:-100px; opacity:0 }
            to { bottom:0px; opacity:1 }
        }

        @keyframes animatebottom {
            from{ bottom:-100px; opacity:0 }
            to{ bottom:0; opacity:1 }
        }

    </style>



@endsection

@section('content')
    <div id="loader"></div>


    <h1 style="font-family: 'bebas_neueregular'; text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.8); font-size:40px;text-align: center">Historia pobrań bazy (ostatnie 20)</h1>
    <hr></br>


    <table id="ilosc" class="table table-striped table-bordered" cellspacing="0" width="50%">
        <thead>
        <th>Miasto</th>
        <th>Województwo</th>
        <th>Data</th>
        <th>BisNode</th>
        <th>Zgody</th>
        <th>Event</th>
        <th>Reszta</th>
        <th>Baza</th>
        <th>Akcja</th>
        </thead>
        <?php foreach ($paczka as  $key):?>
                <tr class ="tabela">
                    <td class="miasto"> {{ $key['miasto'] }} </td>
                    <td> {{ $key['idwoj'] }} </td>
                    <td> {{ $key['date'] }} </td>
                    <td class="baza8"> {{ $key['baza8'] }}</td>
                    <td class="bazazg"> {{ $key['bazazg'] }}</td>
                    <td class="bazaevent"> {{ $key['bazaevent'] }} </td>
                    <td class="bazareszta"> {{ $key['bazareszta'] }} </td>
                    <td> {{ $key['baza'] }} </td>
                    <td><button id = {{ $key['id'].'/'.$key['baza'] }}  type="submit" class="btn btn-default">Pobierz plik</button></td>
                </tr>
        <?php endforeach;?>
    </table>
@endsection

@section('script')
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>

        $('.btn').click(function(event){
            document.getElementById("loader").style.display = "block";
            var dane = event.target.id;
            var baza = dane.split("/");
            var kolumna = $(this).closest('tr');
            var miasto = kolumna.find(".miasto").text();
            var baza8 = kolumna.find(".baza8").text();
            var bazazg = kolumna.find(".bazazg").text();
            var bazaevent = kolumna.find(".bazaevent").text();
            var bazareszta = kolumna.find(".bazareszta").text();

            $.ajax({
                type: "POST",
                url: '{{ url('historyCSV') }}',
                data: {
                    "id": baza[0],
                    "baza":baza[1],
                    "miasto": miasto,
                    "bis": baza8,
                    "zg": bazazg,
                    "reszta": bazareszta,
                    "event": bazaevent
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    window.location="{{URL::to('historyCSVDownload')}}";
                    document.getElementById("loader").style.display = "none";
                }
            });
        });
    </script>
@endsection