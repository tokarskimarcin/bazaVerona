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
        #wybierzplik
        {
            float: left;
            padding-top: 26px;
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


    <h1 style="font-family: 'bebas_neueregular'; text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.8); font-size:40px;text-align: center">Panel zarządzania rekordami</h1>
    <hr></br>


    <form class="form-horizontal" method="post" action="odblokowanie">
        <div class="col-sm-4">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <label>Miasto:</label>
        <input type="text" class="form-control" id="miasto" name="miasto" placeholder="Wpisz nazwę miasta" >
        </div>
        <div class="col-sm-4">
            <div class="form-group" style="margin-top: 24px;">
                <button class="btn btn-primary " name="submit" type="submit">
                    Wyszukaj
                </button>
            </div>
        </div>
    </form>

    <form class="form-horizontal" method="post" action="zablokowanieCSV" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <label>Zablokuj numery poprzez csv(pamiętaj o nagłówku 'telefon')</label>
        <input type="hidden" name="typ" value="bisnode">
        <div>
            <div class="col-xs-6">
                <input type="file" name="import_file" id="wybierzplik"/>
                <br />
                <button type="submit" id="submit" class="btn btn-primary" name="submit" data-loading-text="Loading...">Zablokuj numery</button>
                <hr>
            </div>
        </div>
    </form>




    <table class="table table-bordered">
        <thead>
        <tr style="background: yellow">
            <th>NR</th>
            <th>Miasto</th>
            {{--<th>Wojewodztwo</th>--}}
            <th>Imie Nazwisko</th>
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
            <th>Data pobrania</th>
            <th>Data odblokowania</th>
            <th>Akcja</th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($lista as $item):?>
        <?php

        $s = $item->date;
        $dt = new DateTime($s);
        $date = $dt->format('Y-m-d');
        $data_odblokowania= date('Y-m-d', strtotime($date.'+8 days'));
        ?>

        <?php if($item->baza == 'Wysylka'): ?>
        <tr style="background-color: rgba(250, 235, 215, 0.46)" class="wysylka" id="<?php echo $item->id.'.'.$item->baza ?>">
                <?php else: ?>
         <tr style="background: rgba(216, 245, 251, 0.52)" class="badania" id="<?php echo $item->id.'.'.$item->baza ?>">
             <?php endif;?>
            <td ><b> <?php echo $item->id; ?> </b></td>
            <td ><b> <?php echo $item->miasto; ?> </b></td>
            <td><b> <?php echo $item->name.' '.$item->last; ?> </b></td>
            <td><b> <?php echo $item->baza8; ?> </b></td>
            <td><b> <?php echo $item->baza8Zgody; ?> </b></td>
            <td><b> <?php echo $item->bazazg; ?> </b></td>
            <td><b> <?php echo $item->bazazgZgody; ?> </b></td>
            <td><b> <?php echo $item->bazaevent; ?> </b></td>
            <td><b> <?php echo $item->bazaeventZgody; ?> </b></td>
            <td><b> <?php echo $item->bazareszta; ?> </b></td>
            <td><b> <?php echo $item->bazaresztaZgody; ?> </b></td>
             <td><b> <?php echo $item->bazaexito; ?> </b></td>
             <td><b> <?php echo $item->bazaexitoZgody; ?> </b></td>
            <td><b> <?php echo $item->date; ?> </b></td>
            <td><b> <?php echo $data_odblokowania?> </b></td>
            <td><b> <button type="button" class="btn btn-danger">Odblokuj</button></b></td>
        </tr>
            <?php endforeach;?>
        </tbody>
    </table>

@endsection

@section('script')
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script>
        var trId;
        var server;
        $( ".btn" ).click(function() {
            trId = $(this).closest('tr').prop('id');
            var res = trId.split(".");
            trId = res;
            if (confirm('Czy na pewno chcesz odblokować tą paczkę ??')) {
                document.getElementById("loader").style.display = "block";
                $.ajax({
                    type: "POST",
                    url: '{{ url('unlockredord') }}',
                    data: {
                        "kod": trId
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        server = response;
                        document.getElementById("loader").style.display = "none";
                        alert("Paczka została odblokowana");
                    }
                });
                    $(this).closest("tr").remove();

            } else {
                // Do nothing!
            }



        });
    </script>
@endsection