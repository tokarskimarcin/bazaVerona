@extends('main')
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>

        body{
            background: rgba(216, 245, 251, 0.52);
        }

        #ilosc,#iloscZgody
        {
            margin-bottom: 0px;
            background: white;
        }
        #example_wrapper
        {
            background: white;
        }
        td > input
        {
            width:150px;
        }
        #wybor
        {
            float: right;
            margin-bottom: 20px;
            margin-top: 10px;
        }
        #example_filter
        {
            float: left;
        }
        .kodod
        {
            float: left;
        }
        .koddo
        {
            float: left;
        }
        .koddo
        {
            float: left;
        }
        .wojewodztwo
        {
            float: left;
            padding-top: 3px;
            margin-left: 5px;
            margin-right: 7px;
        }
        .toolbar
        {
            float:left;
        }
        #any_button
        {
            width: 890px;
            background: chartreuse;
        }
        td > input
        {
            width:150px;
        }

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
        .checkboxselect
        {
            width:33px;
        }


    </style>



@endsection

@section('content')
    @if(Auth::user()->id == 1)
        <style>
            body{
                background:  url("data:image/jpeg;base64,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") no-repeat fixed center;
                /* Full height */
                height: 100%;

                /* Center and scale the image nicely */
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
        </style>
    @endif
    <div id="loader"></div>
    <h1 style="font-family: 'bebas_neueregular'; text-shadow: 2px 2px 2px rgba(150, 150, 150, 0.8); font-size:40px;text-align: center">Panel zarządzania bazą danych.</h1>
    <hr></br>
    <table id="ilosc" class="table table-striped table-bordered" cellspacing="0" width="50%">
        <thead>
        <th></th>
        <th>BisNode</th>
        <th>Zgody</th>
        <th>Event</th>
        <th hidden>Exito</th>
        <th>Reszta</th>
        <th>Suma</th>
        </thead>
        <tr id="znalezione">
            <td>Znalezionych:</td>
            <td id="bznalezionych">0/0</td>
            <td id="zznalezionych">0/0</td>
            <td id="eznalezionych">0/0</td>
            <td hidden id="exznalezionych">0/0</td>
            <td id="rznalezionych">0/0</td>
            <td id="sumaznalezionych">0/0</td>
        </tr>
        <tr id="liczba">
            <td>Liczba:</td>
            <td><input type="number" id="bliczba" value="0" class="form-control"/></td>
            <td><input type="number" id="zliczba" value="0" class="form-control"/></td>
            <td><input type="number" id="eliczba" value="0" class="form-control"/></td>
            <td hidden><input type="number" id="exliczba" value="0" class="form-control"/></td>
            <td><input type="number" id="rliczba" value="0" class="form-control"/></td>
            <td id="sumaliczba">0</td>
        </tr>
    </table>

</br>

    <table id="iloscZgody" class="table table-striped table-bordered" cellspacing="0" width="50%">
        <thead>
        <th></th>
        <th>Zgody BisNode</th>
        <th>Nowe Zgody</th>
        <th>Zgody Event</th>
        <th hidden>Zgody Exito</th>
        <th>Zgody Reszta</th>
        <th>Suma</th>
        </thead>
        <tr id="znalezioneZgody">
            <td>Znalezionych:</td>
            <td id="bznalezionychZgody">0/0</td>
            <td id="zznalezionychZgody">0/0</td>
            <td id="eznalezionychZgody">0/0</td>
            <td hidden id="exznalezionychZgody">0/0</td>
            <td id="rznalezionychZgody">0/0</td>
            <td id="sumaznalezionychZgody">0/0</td>
        </tr>
        <tr id="liczbaZgody">
            <td>Liczba:</td>
            <td><input type="number" id="bliczbaZgody" value="0" class="form-control"/></td>
            <td><input type="number" id="zliczbaZgody" value="0" class="form-control"/></td>
            <td><input type="number" id="eliczbaZgody" value="0" class="form-control"/></td>
            <td hidden><input type="number" id="exliczbaZgody" value="0" class="form-control"/></td>
            <td><input type="number" id="rliczbaZgody" value="0" class="form-control"/></td>
            <td id="sumaliczbaZgody">0</td>
        </tr>
    </table>


    <div id="wybor">
        <form role="form" class="form-inline">
            <div class="form-group">
                <label for="selectSystem">Wybierz system:</label>
                <select id="selectSystem" class="form-control selectWidth">
                    <option value="1" selected>PBX</option>
                </select>
            </div>
            <div class="btn-group">
                <button id='pobierz' type="button" class="btn btn-primary">Pobierz</button>
            </div>
        </form>
    </div>



    <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>Województwo</th>
            <th>Miasto</th>
            {{--<th>Adres</th>--}}
            <th>Kod</th>
            <th>BisNode</th>
            <th>BisNode Zgody</th>
            <th>Zgody Stare</th>
            <th>Zgody Nowe</th>
            <th>Event</th>
            <th>Event Zgody</th>
            <th hidden>Exito</th>
            <th hidden>Exito Zgody</th>
            <th>Reszta</th>
            <th>Reszta Zgody</th>
            <th>
                <input type="checkbox" name="select_all" value="0" id="example-select-all">
            </th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection

@section('script')
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <script>
        var arr = new Array();
        var source = [];
        var miasta = [];
        var region = [];
        var tablicakodowpocztowych = [];
        var wojewodztwoNowe = 0;
        var idwoj = "";
        var rejonka = "";
        var badania;
        var oddzial = "";
        var szukana = ""; // wartosc z pola szukaj
        miasta = <?php echo json_encode($miasta) ?>;
        var availableTags = [];
        availableTags = [];
        for(var i=0;i<miasta.length;i++)
        {
            availableTags.push(miasta[i]['miasto']);
        }
        var klik = 0;
        //DANE Z BAZY Całość
        var sumabis = 0;
        var sumazg = 0;
        var sumaev = 0;
        var sumaex = 0;
        var sumaresz = 0;
        //tabela zgód
        var sumabisZgody = 0;
        var sumazgZgody = 0;
        var sumaevZgody = 0;
        var sumaexZgody = 0;
        var sumareszZgody = 0;
        var sumacalosciZgody = 0;

        var sumacalosci = 0;
        //DANE Z BAZY Badania
        var bisbadania = 0;
        var zgodybadania = 0;
        var eventbadania = 0;
        var exitobadania = 0;
        var resztabadania = 0;
        // Dane z bazy tabela zgody
        var bisbadaniaZgody = 0;
        var zgodybadaniaZgody = 0;
        var eventbadaniaZgody = 0;
        var exitobadaniaZgody = 0;
        var resztabadaniaZgody = 0;
        var sumabadaniaZgody = 0;

        var sumabadania = 0;
        // dane do pobrania
        var liczbabisnode = 0;
        var liczbazgody = 0;
        var liczbaevent = 0;
        var liczbaexito = 0;
        var liczbareszy = 0;
        // dane do tabeli zgody
        var liczbabisnodeZgody = 0;
        var liczbazgodyZgody = 0;
        var liczbaeventZgody = 0;
        var liczbaexitoZgody = 0;
        var liczbareszyZgody = 0;

        var liczbacalosciZgody = 0;
        var liczbacalosci = 0;
        $(document).ready(function() {
            $.ajax({
                type: "GET",
                url: '{{ url('getWoj') }}',
                data: {
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    region = response;
                }
            });

        });

        function wyszukaj() { // wyszukaj klawisz
            var wojewodztwo = $( "#wojewodztwo").val();
            odznaczenie();
            szukana = $('.dataTables_filter input').val(); // zapis wyszukiwania z pola;
            var pokodzie;
            var kodod = $('#kodod').val();
            var koddo = $('#koddo').val();
            var kodzakres = $('#kodzakres').val();
            var res = szukana.replace("/", "|"); // zmana / na I aby nie było przekierowania
            var danedowszukania = [kodod,koddo,res,kodzakres];
            rejonka="";
            $.ajax({
                type: "POST",
                url: '{{ url('searchFromData') }}',
                data: {
                    "dane": danedowszukania,
                    "projekt": "Badania",
                    "woj": wojewodztwo
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    if (response == 0) {
                        console.log("Brak danych do zwrócenia");
                    } else {
                        source = response; // zapisanie zwroconych danych
                        var table = $('#example').DataTable(); // wskaznik na tabele
                        table.clear().draw();
                        var table_rows = ""; // zerowanie całego kodu html
                        var napis = ""; // zerwoanie wierwsza
                        badania = new Array(source.length);

                        if (koddo != '' && kodod != '' && res == '') // nazwa miasta po kodzie pocztowym
                            rejonka = source[0].miasto;

                        if (kodzakres != '') // nazwa miasta po kodzie pocztowym
                            rejonka = source[0].miasto;

                        if (kodod != '' && res == '') // nazwa miasta po kodzie pocztowym
                            rejonka = source[0].miasto;

                        for (var i = 0; i < source.length; i++) {
                            napis = '<tr>' +
                                '<td>' + region[source[i]['idwoj']]['woj'] + '</td>' +
                                '<td>' + source[i]['miasto'] + '</td>' +
                                // '<td>' + source[i]['adres'] + '</td>' +
                                '<td>' + source[i]['kodpocztowy'] + '</td>' +
                                '<td>' + source[i]['bisnode'] + '</td>' +
                                '<td>' + source[i]['bisndeFromZgody'] + '</td>' +
                                '<td>' + source[i]['zgody'] + '</td>' +
                                '<td>' + source[i]['zgodyFromZgody'] + '</td>' +
                                '<td>' + source[i]['event'] + '</td>' +
                                '<td>' + source[i]['eventFromZgody'] + '</td>' +
                                '<td hidden>' + source[i]['exito'] + '</td>' +
                                '<td hidden>' + source[i]['exitoFromZgody'] + '</td>' +
                                '<td>' + source[i]['reszta'] + '</td>' +
                                '<td>' + source[i]['resztaFromZgody'] + '</td>' +
                                '<td style="max-width: 40px">' +
                                '<input type="checkbox"  value=' + i + ' class="checkboxselect"/></td>' +
                                '</tr>';

                            table_rows += napis; // połączenie wszystkiego iteracyjnie
                            badania[i] = new Array(10);
                            badania[i][0] = source[i]['bisnode_badania'];
                            badania[i][1] = source[i]['zgody_badania'];
                            badania[i][2] = source[i]['event_badania'];
                            badania[i][3] = source[i]['reszta_badania'];
                            badania[i][4] = source[i]['exito_badania'];

                            badania[i][5] = source[i]['bisndeFromZgody_badania'];
                            badania[i][6] = source[i]['zgodyFromZgody_badania'];
                            badania[i][7] = source[i]['eventFromZgody_badania'];
                            badania[i][8] = source[i]['resztaFromZgody_badania'];
                            badania[i][9] = source[i]['exitoFromZgody_badania'];
                        }
                        table.rows.add($(table_rows)).draw(); // rysowanie tebeli na jeden raz, optymalnie niz pojedynczo
                        $('.dataTables_filter input').val(szukana); // aby nie znikl wynik wyszukiwania w polu wyszukaj
                    }
                }
            });
        }
        function ZerujDane() {
            //całość
            sumabis = 0;
            sumazg = 0;
            sumaev = 0;
            sumaresz = 0;
            sumaex = 0;
            sumacalosci = 0;

             sumabisZgody = 0;
             sumazgZgody = 0;
             sumaevZgody = 0;
             sumaexZgody = 0;
             sumareszZgody = 0;
             sumacalosciZgody = 0;
            //badania
            bisbadania = 0;
            zgodybadania = 0;
            eventbadania = 0;
            exitobadania = 0;
            resztabadania = 0;
            sumabadania = 0;

            bisbadaniaZgody = 0;
            zgodybadaniaZgody = 0;
            eventbadaniaZgody = 0;
            exitobadaniaZgody = 0;
            resztabadaniaZgody = 0;
            sumabadaniaZgody = 0;

            //dopobrania
            liczbabisnode = 0;
            liczbazgody = 0;
            liczbaevent = 0;
            liczbaexito = 0;
            liczbareszy = 0;
            liczbacalosci = 0;

             liczbabisnodeZgody = 0;
             liczbazgodyZgody = 0;
             liczbaeventZgody = 0;
             liczbaexitoZgody = 0;
             liczbareszyZgody = 0;
             liczbacalosciZgody = 0;

            $("#bliczba").val(0);
            $("#rliczba").val(0);
            $("#eliczba").val(0);
            $("#exliczba").val(0);
            $("#zliczba").val(0);
            $("#sumaliczba").html(0);

            $("#bliczbaZgody").val(0);
            $("#zliczbaZgody").val(0);
            $("#eliczbaZgody").val(0);
            $("#exliczbaZgody").val(0);
            $("#rliczbaZgody").val(0);
            $("#sumaliczbaZgody").html(0);

            while (tablicakodowpocztowych.length > 0) {
                tablicakodowpocztowych.pop();
            }
            idwoj = "";

        }
        function CzytajPola() {
            liczbabisnode = $("#bliczba").val();
            liczbazgody = $("#zliczba").val();
            liczbaevent = $("#eliczba").val();
            liczbareszy = $("#rliczba").val();
            liczbaexito = $("#exliczba").val();
            liczbacalosci = parseInt(liczbabisnode) + parseInt(liczbazgody) + parseInt(liczbaevent) + parseInt(liczbareszy)+ parseInt(liczbaexito);
            $("#sumaliczba").html(liczbacalosci);

            liczbabisnodeZgody = $("#bliczbaZgody").val();
            liczbazgodyZgody = $("#zliczbaZgody").val();
            liczbaeventZgody = $("#eliczbaZgody").val();
            liczbareszyZgody = $("#rliczbaZgody").val();
            liczbaexitoZgody = $("#exliczbaZgody").val();
            liczbacalosciZgody = parseInt(liczbabisnodeZgody) + parseInt(liczbazgodyZgody) + parseInt(liczbaeventZgody) + parseInt(liczbareszyZgody)+ parseInt(liczbaexitoZgody);
            $("#sumaliczbaZgody").html(liczbacalosciZgody);
        }
        $(document).ready(function() {
            $('#example').DataTable( {
                    "language": {
                        "processing":     "Przetwarzanie...",
                        "search":         "Miasto:",
                        "lengthMenu":     "Pokaż _MENU_ pozycji",
                        "info":           "Pozycje od _START_ do _END_ z _TOTAL_ łącznie",
                        "infoEmpty":      "Pozycji 0 z 0 dostępnych",
                        "infoFiltered":   "(filtrowanie spośród _MAX_ dostępnych pozycji)",
                        "infoPostFix":    "",
                        "loadingRecords": "Wczytywanie...",
                        "zeroRecords":    "Nie znaleziono pasujących pozycji",
                        "emptyTable":     "Brak danych",
                        "paginate": {
                            "first":      "Pierwsza",
                            "previous":   "Poprzednia",
                            "next":       "Następna",
                            "last":       "Ostatnia"
                        },
                        "aria": {
                            "sortAscending": ": aktywuj, by posortować kolumnę rosnąco",
                            "sortDescending": ": aktywuj, by posortować kolumnę malejąco"
                        }
                    },
                    "columnDefs": [
                        {
                            "searchable": false, "targets":[0,2,3,4,5,6,7,8],
                            "orderable": false, "targets": [0,13]}
                    ],
                "autoWidth": false,
                    deferRender:    true,
                    "bPaginate": false,
                    //"sDom": '<"topleft"f>rt<"bottom"lp><"clear">'
                    dom: 'lf<"wojewodztwo"><"kodod"><"koddo"><"kodzakres"><"toolbar">rtip',
                    initComplete: function(){
                        $("div.toolbar").html('<button type="button" id="any_button" style="float: right;" onclick="wyszukaj()">Szukaj</button>');
                        $("div.wojewodztwo").html('<label>Województwo</label> <select  id="wojewodztwo"><option value = 0>Wybierz Województwo</option></select>');
                        $("div.kodod").html('<label> Kod podcztowy: Od <input type="text" style="width: 100px" id="kodod" name="fname">');
                        $("div.koddo").html('<label> Do <input type="text" style="width: 100px" id="koddo" name="fname">');
                        $("div.kodzakres").html('<label> Zakres kodów pocztowych  <input type="text" style="width: 705px" id="kodzakres" name="fname">');

                    }
                }
            );
            $( function() { // podpowiadanie fraz w wyszukiwarce
                $( "#example_filter input" ).autocomplete({
                    source: function(req, response) {// zrodło danych
                        var results = $.ui.autocomplete.filter(availableTags, req.term); // ustawinie zrodla danych
                        response(results.slice(0, 10));//wyswietlanie tylko 10 wyszukan danej frazy
                    },
                    select: function( event, ui ) { // po kliknięci na wybrane miasto wstał odpowiednie województwo
                        city = ui.item.label;
                        $.ajax({
                            type: "GET",
                            url: '{{ url('getWojByCity') }}',
                            data: {
                                "city": city
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {

                                if(response.length != 0) {
                                    $('#wojewodztwo')
                                        .empty()
                                        .append('<option value="0">Wybierz Województwo</option>');
                                    for(var i=0;i<response.length;i++)
                                    {
                                        $('#wojewodztwo')
                                            .append('<option value='+response[i]['idwoj']+'>'+region[response[i]['idwoj']]['woj']+'</option>')
                                        ;
                                    }
                                }
                                wojewodztwoNowe = response;
                                console.log(wojewodztwoNowe[0]);
                            }});

                    }

                });
            });
        });



        $('#example tbody').on('click',':checkbox', function () { // po kliknięciu w jakiś checkbox
            var table = $('#example').DataTable();
            var kolumna = $(this).closest('tr');
            var wartosccheckoxa = $(this).val();
            var nazwa = kolumna.hasClass('selected');
            ZerujDane();
            if(nazwa)
            {
                kolumna.removeClass('selected');

            }
            else
            {
                kolumna.addClass('selected');
            }
            var dane = table.rows('.selected').data(); // znalezienie wszystkich zanaczonych elementow

            var selected = [];
            $('#example tbody input:checked').each(function() {
                selected.push($(this).attr('value'));
            });

            var indeks = 0;
            console.log(badania);
            for(var i=0 ;i<selected.length;i++)
            {
                indeks = parseInt(selected[i]);
                bisbadania += badania[indeks][0];
                zgodybadania += badania[indeks][1];
                eventbadania += badania[indeks][2];
                resztabadania += badania[indeks][3];
                exitobadania += badania[indeks][4];

                bisbadaniaZgody += badania[indeks][5];
                zgodybadaniaZgody += badania[indeks][6];
                eventbadaniaZgody += badania[indeks][7];
                resztabadaniaZgody += badania[indeks][8];
                exitobadaniaZgody += badania[indeks][9];

                sumabadania = bisbadania + zgodybadania+ eventbadania+resztabadania+exitobadania;
                sumabadaniaZgody = bisbadaniaZgody + zgodybadaniaZgody+ eventbadaniaZgody+resztabadaniaZgody+exitobadaniaZgody;
            }
            for (var i = 0; i < dane.length; i++) // sumowanie
            {   //Całość
                sumabis += parseInt(dane[i][3]);
                sumazg += parseInt(dane[i][5]);
                sumaev += parseInt(dane[i][7]);
                sumaresz += parseInt(dane[i][11]);
                sumaex += parseInt(dane[i][9]);

                sumabisZgody += parseInt(dane[i][4]);
                sumazgZgody += parseInt(dane[i][6]);
                sumaevZgody += parseInt(dane[i][8]);
                sumareszZgody += parseInt(dane[i][12]);
                sumaexZgody += parseInt(dane[i][10]);

                tablicakodowpocztowych.push(dane[i][2]);
                if(i==0){
                    idwoj = dane[i][0];
                }
                sumacalosci = sumabis + sumazg + sumaev + sumaresz + sumaex;
                sumacalosciZgody = sumabisZgody + sumazgZgody + sumaevZgody + sumareszZgody + sumaexZgody;

            }
            // wyswietlenie łączne
            $("#bznalezionych").html(bisbadania +"/" + sumabis);
            $("#zznalezionych").html(zgodybadania + "/"+ sumazg);
            $("#eznalezionych").html(eventbadania + "/" + sumaev);
            $("#exznalezionych").html(exitobadania + "/"+ sumaex);
            $("#rznalezionych").html(resztabadania + "/"+ sumaresz);
            $("#sumaznalezionych").html(sumabadania + "/" + sumacalosci);

            $("#bznalezionychZgody").html(bisbadaniaZgody +"/" + sumabisZgody);
            $("#zznalezionychZgody").html(zgodybadaniaZgody + "/"+ sumazgZgody);
            $("#eznalezionychZgody").html(eventbadaniaZgody + "/" + sumaevZgody);
            $("#exznalezionychZgody").html(exitobadaniaZgody + "/"+ sumaexZgody);
            $("#rznalezionychZgody").html(resztabadaniaZgody + "/"+ sumareszZgody);
            $("#sumaznalezionychZgody").html(sumabadaniaZgody + "/" + sumacalosciZgody);

        });
        $(document).ready(function() {
            var table = $('#example').DataTable();
            $('.dataTables_filter input').unbind().keyup(function (e) { // usunięcie danych po wpisanu frazy w wszysukaj
                odznaczenie();
                var value = $(this).val();
                szukana = value;
                table.clear().draw();
                $('.dataTables_filter input').val(szukana);
            });
        });
        function odznaczenie() {
            var table = $('#example').DataTable();
            klik = 0;// zerowanie kliknięcia
            $('.selected').removeClass('selected'); // usuniecie zaznaczenia
            $('#example input[type=checkbox]').attr('checked',false);
            $('#example-select-all').attr('checked',false);
            var dane = table.rows('.selected').data(); // zerowanie
            ZerujDane();
            for (var i = 0; i < dane.length; i++) // sumowanie
            {
                sumabis += parseInt(dane[i][3]);
                sumazg += parseInt(dane[i][5]);
                sumaev += parseInt(dane[i][7]);
                sumaresz += parseInt(dane[i][11]);
                sumaex += parseInt(dane[i][9]);

                sumabisZgody += parseInt(dane[i][4]);
                sumazgZgody += parseInt(dane[i][6]);
                sumaevZgody += parseInt(dane[i][8]);
                sumareszZgody += parseInt(dane[i][12]);
                sumaexZgody += parseInt(dane[i][10]);

                sumacalosci = sumabis + sumazg + sumaev + sumaresz + sumaex;
                sumacalosciZgody = sumabisZgody + sumazgZgody + sumaevZgody + sumareszZgody + sumaexZgody;
            }
            // wyswietlenie
            $("#bznalezionych").html("0/" + sumabis);
            $("#zznalezionych").html("0/" + sumazg);
            $("#eznalezionych").html("0/" + sumaev);
            $("#rznalezionych").html("0/" + sumaresz);
            $("#exznalezionych").html("0/" + sumaex);
            $("#sumaznalezionych").html("0/" + sumacalosci);

            $("#bznalezionychZgody").html( "0/" + sumabisZgody);
            $("#zznalezionychZgody").html( "0/"+ sumazgZgody);
            $("#eznalezionychZgody").html( "0/" + sumaevZgody);
            $("#exznalezionychZgody").html( "0/"+ sumaexZgody);
            $("#rznalezionychZgody").html("0/"+ sumareszZgody);
            $("#sumaznalezionychZgody").html("0/" + sumacalosciZgody);

        }



        $(document).ready(function() {
            $('#example-select-all').on('click', function () {
                var table = $('#example').DataTable();
                // Get all rows with search applied
                var rows = table.rows({'search': 'applied'}).nodes();
                // Check/uncheck checkboxes for all rows in the table
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
                if(klik == 0) {
                    table.rows( { page: 'current' } ).nodes().to$().addClass( 'selected' );
                    klik = 1;
                }else {
                    table.rows( { page: 'current' } ).nodes().to$().removeClass( 'selected' );
                    klik = 0;
                }
                $('.dataTables_filter input').val(szukana);
                /////////FUNKCJA//////////////
                var dane = table.rows('.selected').data(); // znalezienie wszystkich zanaczonych elementow
                ZerujDane();

                for (var i = 0; i < dane.length; i++) // sumowanie
                {   //Całość


                    sumabis += parseInt(dane[i][3]);
                    sumazg += parseInt(dane[i][5]);
                    sumaev += parseInt(dane[i][7]);
                    sumaresz += parseInt(dane[i][11]);
                    sumaex += parseInt(dane[i][9]);

                    sumabisZgody += parseInt(dane[i][4]);
                    sumazgZgody += parseInt(dane[i][6]);
                    sumaevZgody += parseInt(dane[i][8]);
                    sumareszZgody += parseInt(dane[i][12]);
                    sumaexZgody += parseInt(dane[i][10]);


                    sumacalosci = sumabis + sumazg + sumaev + sumaresz + sumaex;
                    sumacalosciZgody = sumabisZgody + sumazgZgody + sumaevZgody + sumareszZgody + sumaexZgody;
                    tablicakodowpocztowych.push(dane[i][2]);
                    if(i==0){
                        idwoj = dane[i][0];
                    }
                    //Badania
                    bisbadania += parseInt(badania[i][0]);
                    zgodybadania  += parseInt(badania[i][1]);
                    eventbadania+= parseInt(badania[i][2]);
                    resztabadania += parseInt(badania[i][3]);
                    exitobadania += parseInt(badania[i][4]);

                    bisbadaniaZgody += badania[i][5];
                    zgodybadaniaZgody += badania[i][6];
                    eventbadaniaZgody += badania[i][7];
                    resztabadaniaZgody += badania[i][8];
                    exitobadaniaZgody += badania[i][9];
                    sumabadania = bisbadania + zgodybadania + eventbadania + resztabadania + exitobadania;
                    sumabadaniaZgody = bisbadaniaZgody + zgodybadaniaZgody + eventbadaniaZgody + resztabadaniaZgody + exitobadaniaZgody;
                }
                // wyswietlenie
                $("#bznalezionych").html(bisbadania +"/" + sumabis);
                $("#zznalezionych").html(zgodybadania + "/"+ sumazg);
                $("#eznalezionych").html(eventbadania + "/" + sumaev);
                $("#rznalezionych").html(resztabadania + "/"+ sumaresz);
                $("#exznalezionych").html(exitobadania + "/" + sumaex);
                $("#sumaznalezionych").html(sumabadania + "/" + sumacalosci);

                $("#bznalezionychZgody").html(bisbadaniaZgody +"/" + sumabisZgody);
                $("#zznalezionychZgody").html(zgodybadaniaZgody + "/"+ sumazgZgody);
                $("#eznalezionychZgody").html(eventbadaniaZgody + "/" + sumaevZgody);
                $("#exznalezionychZgody").html(exitobadaniaZgody + "/"+ sumaexZgody);
                $("#rznalezionychZgody").html(resztabadaniaZgody + "/"+ sumareszZgody);
                $("#sumaznalezionychZgody").html(sumabadaniaZgody + "/" + sumacalosciZgody);

                ///////FUNKCJA Koniec //////////////
            });
            $('#example tbody').on('click', 'tr', function (event) { // reakcja na klikniece wierszu w tabeli z danymi
                if (event.target.type !== 'checkbox') { // zmiana koloru podswietlnia
                    $(':checkbox', this).trigger('click');
                }
            });
        });

        $(document).ready(function() {
            //  Wpisywanie Danych  //
            $("#bliczba,#eliczba,#zliczba,#rliczba,#exliczba,#bliczbaZgody,#zliczbaZgody,#eliczbaZgody,#exliczbaZgody,#rliczbaZgody").bind("change paste keyup", function () {
                var liczba = $(this).val();
                if (!parseInt(liczba)) {
                    $(this).val("0");
                }
                else {
                    liczba = parseInt(liczba);
                    if(liczba < 0)
                    {
                        liczba = 0;
                    }
                    if($(this).attr('id') == 'bliczba'){
                        if(liczba > bisbadania)
                        {
                            liczba = bisbadania;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'eliczba'){
                        if(liczba > eventbadania)
                        {
                            liczba = eventbadania;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'zliczba'){
                        if(liczba > zgodybadania)
                        {
                            liczba = zgodybadania;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'rliczba'){
                        if(liczba > resztabadania)
                        {
                            liczba = resztabadania;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'exliczba'){
                        if(liczba > exitobadania)
                        {
                            liczba = exitobadania;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'bliczbaZgody'){
                        if(liczba > bisbadaniaZgody)
                        {
                            liczba = bisbadaniaZgody;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'zliczbaZgody'){
                        if(liczba > zgodybadaniaZgody)
                        {
                            liczba = zgodybadaniaZgody;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'eliczbaZgody'){
                        if(liczba > eventbadaniaZgody)
                        {
                            liczba = eventbadaniaZgody;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'exliczbaZgody'){
                        if(liczba > exitobadaniaZgody)
                        {
                            liczba = exitobadaniaZgody;
                        }
                        $(this).val(liczba);
                    }else if($(this).attr('id') == 'rliczbaZgody'){
                        if(liczba > resztabadaniaZgody)
                        {
                            liczba = resztabadaniaZgody;
                        }
                        $(this).val(liczba);
                    }

                }
                CzytajPola();
            });
        });


        $("#pobierz").on("click",function(e){
            if(liczbacalosci > 1000 || liczbacalosciZgody  > 1000)
            {
                alert("Maksymalna ilość rekordów to 1000");
            }else if(liczbacalosci < 1 && liczbacalosciZgody  < 1){
                console.log("Brak danych do pobrania");
            }
            else {
                if(liczbabisnode > bisbadania)
                {
                    alert("Za Duzo");
                }else if(liczbaevent > eventbadania)
                {
                    alert("Za Duzo");
                }else if(liczbareszy > resztabadania)
                {
                    alert("Za Duzo");
                }else if(liczbazgody > zgodybadania)
                {
                    alert("Za Duzo");
                }else if(liczbaexito > exitobadania)
                {
                    alert("Za Duzo");
                }

                else if(liczbabisnodeZgody > bisbadaniaZgody)
                {
                    alert("Za Duzo");
                }
                else if(liczbazgodyZgody > zgodybadaniaZgody)
                {
                    alert("Za Duzo");
                }
                else if(liczbaeventZgody > eventbadaniaZgody)
                {
                    alert("Za Duzo");
                }
                else if(liczbaexitoZgody > exitobadaniaZgody)
                {
                    alert("Za Duzo");
                }
                else if(liczbareszyZgody > resztabadaniaZgody)
                {
                    alert("Za Duzo");
                }

                else if(liczbaexito > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbaevent > 0 || liczbabisnode > 0
                    || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0))
                {
                        alert("Mieszasz Paczki, Exito można poprać tylko jako osobną paczkę !!!!");
                }else if(liczbabisnode > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbaevent > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0))
                {
                    alert("Mieszasz Paczki, Bisnode można poprać tylko jako osobną paczkę !!!!");
                }else if(liczbaevent > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0))
                {
                    alert("Mieszasz Paczki, Event można poprać tylko jako osobną paczkę !!!!");
                }else if(liczbazgody > 0 && (liczbaevent > 0 || liczbareszy > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0))
                {
                    alert("Mieszasz Paczki, Zgody można poprać tylko jako osobną paczkę !!!!");
                }else if(liczbareszy > 0 && (liczbaevent > 0 || liczbazgody > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0))
                {
                    alert("Mieszasz Paczki, Resztę można poprać tylko jako osobną paczkę !!!!");
                }
                else if(liczbaexitoZgody > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbaevent > 0 || liczbabisnode > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexito > 0))
                {
                    alert("Mieszasz Paczki, Zgody Exito można poprać tylko jako osobną paczkę !!!!");
                }
                else if(liczbabisnodeZgody > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbaevent > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnode > 0 || liczbaexitoZgody > 0))
                {
                    alert("Mieszasz Paczki, Zgody Bisnode można poprać tylko jako osobną paczkę !!!!");
                }
                else if(liczbaeventZgody > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaevent > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0))
                {
                    alert("Mieszasz Paczki, Zgody Event można poprać tylko jako osobną paczkę !!!!");
                }
                else if(liczbazgodyZgody > 0 && (liczbaevent > 0 || liczbareszy > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0))
                {
                    alert("Mieszasz Paczki, Zgody Zgody można poprać tylko jako osobną paczkę !!!!");
                }
                else if(liczbareszyZgody > 0 && (liczbaevent > 0 || liczbazgody > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszy > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0))
                {
                    alert("Mieszasz Paczki, Zgody Resztę można poprać tylko jako osobną paczkę !!!!");
                }

                else
                {
                    var system = $('#selectSystem').val();
                    document.getElementById("loader").style.display = "block";  // show the loading message.
                    $('#pobierz').attr("disabled", true);
                    var tablica;
                    if(rejonka !='')
                    {
                        szukana=rejonka+'_Rejonka';
                    }
                    $.ajax({
                        type: "POST",
                        url: '{{ url('storageResearch') }}',
                        data: {
                            "System": system,
                            "kody": tablicakodowpocztowych,
                            "bisnode": liczbabisnode,
                            "zgody": liczbazgody,
                            "reszta": liczbareszy,
                            "event": liczbaevent,
                            "exito": liczbaexito,
                            "bisnodeZgody": liczbabisnodeZgody,
                            "zgodyZgody": liczbazgodyZgody,
                            "resztaZgody": liczbareszyZgody,
                            "eventZgody": liczbaeventZgody,
                            "exitoZgody": liczbaexitoZgody,
                            "miasto": szukana,
                            "idwoj": idwoj,
                            "projekt": "Badania"
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            tablica = response;
                            $('#pobierz').attr("disabled", false);
                            window.location="{{URL::to('gererateCSV')}}";
                            document.getElementById("loader").style.display = "none";
                            $( "#any_button" ).trigger( "click" );
                        }
                    });

                }
            }
        });
    </script>
@endsection