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
    <div class="btn-group">
        <button id='pobierz' type="button" class="btn btn-primary">Wgraj rekordy do bazy</button>
    </div>
<h2>Ilość rekordów w pliku: {{count($dane)}}</h2>
    <div id="status">

    </div>

<hr>
<h2>Podgląd próbki rekordów: </h2>
    <table id="ilosc" class="table table-striped table-bordered" cellspacing="0" width="50%">
        <thead>
            <tr>
                <th>Lp.</th>
                @foreach($naglowki as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>

        @for ($i = 0; $i < 2; $i++)
            <tr>
                <td>{{$i+1}}</td>
                @foreach($naglowki as $item)
                    <td>{{ $dane[$i][$item] }}</td>
                @endforeach
            </tr>
        @endfor
    </table>

@endsection





@section('script')
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        typ = <?php echo json_encode($typ) ?>;
        $("#pobierz").on("click",function(e) {
            document.getElementById("loader").style.display = "block";  // show the loading message.
            $.ajax({
                type: "Post",
                url: '{{ url('save') }}',
                data: {
                    base: typ
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    tablica = response;
                    console.log(tablica);
                    document.getElementById("loader").style.display = "none";
                    $( "#pobierz" ).remove();
                    $( "#status" ).append( "<h4>Ilość aktualizacji: "+tablica[0]+" ---  Nowe rekordy: "+tablica[1]+"</h4>" );
                }
            });
        });

    </script>
@endsection