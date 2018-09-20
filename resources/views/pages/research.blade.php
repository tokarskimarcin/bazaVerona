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
            @if(Auth::user()->dep_id == 1)
            <div class="form-group">
                <label for="cellPhoneSystem">Typ pobrania:</label>
                <select id="cellPhoneSystem" class="form-control selectWidth">
                    <option value="1">Komórkowe + Stacjonarne</option>
                    <option value="2">Komórkowe</option>
                    <option value="3">Stacjonarne</option>
                </select>
            </div>
            @endif
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
            <th>Exito</th>
            <th>Exito Zgody</th>
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
        var globalCity;
        var arr = new Array();
        var userType = '{{Auth::user()->dep_id}}';
        let userID = '{{Auth::user()->id}}';
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
                        {"searchable": false, "targets":[0,2,3,4,5,6,7,8]},
                        {"orderable": false, "targets": [0,13]},
                        {"visible": false, "targets": [9,10] },
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

            if(userType != 1) {
                console.log(userID);
                if (liczbacalosci > 1000 || liczbacalosciZgody > 1000) {
                    alert("Maksymalna ilość rekordów to 1000");
                } else if (liczbacalosci < 1 && liczbacalosciZgody < 1) {
                    alert("Brak danych do pobrania");
                }
                else {
                    if (liczbabisnode > bisbadania) {
                        alert("Za Duzo");
                    } else if (liczbaevent > eventbadania) {
                        alert("Za Duzo");
                    } else if (liczbareszy > resztabadania) {
                        alert("Za Duzo");
                    } else if (liczbazgody > zgodybadania) {
                        alert("Za Duzo");
                    } else if (liczbaexito > exitobadania) {
                        alert("Za Duzo");
                    }

                    else if (liczbabisnodeZgody > bisbadaniaZgody) {
                        alert("Za Duzo");
                    }
                    else if (liczbazgodyZgody > zgodybadaniaZgody) {
                        alert("Za Duzo");
                    }
                    else if (liczbaeventZgody > eventbadaniaZgody) {
                        alert("Za Duzo");
                    }
                    else if (liczbaexitoZgody > exitobadaniaZgody) {
                        alert("Za Duzo");
                    }
                    else if (liczbareszyZgody > resztabadaniaZgody) {
                        alert("Za Duzo");
                    }

                    else if (liczbaexito > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbaevent > 0 || liczbabisnode > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Exito można poprać tylko jako osobną paczkę !!!!");
                    } else if (liczbabisnode > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbaevent > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Bisnode można poprać tylko jako osobną paczkę !!!!");
                    } else if (liczbaevent > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Event można poprać tylko jako osobną paczkę !!!!");
                    } else if (liczbazgody > 0 && (liczbaevent > 0 || liczbareszy > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Zgody można poprać tylko jako osobną paczkę !!!!");
                    } else if (liczbareszy > 0 && (liczbaevent > 0 || liczbazgody > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Resztę można poprać tylko jako osobną paczkę !!!!");
                    }
                    else if (liczbaexitoZgody > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbaevent > 0 || liczbabisnode > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexito > 0)) {
                        alert("Mieszasz Paczki, Zgody Exito można poprać tylko jako osobną paczkę !!!!");
                    }
                    else if (liczbabisnodeZgody > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbaevent > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnode > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Zgody Bisnode można poprać tylko jako osobną paczkę !!!!");
                    }
                    else if (liczbaeventZgody > 0 && (liczbazgody > 0 || liczbareszy > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszyZgody > 0 || liczbaevent > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Zgody Event można poprać tylko jako osobną paczkę !!!!");
                    }
                    else if (liczbazgodyZgody > 0 && (liczbaevent > 0 || liczbareszy > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgody > 0 || liczbareszyZgody > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Zgody Zgody można poprać tylko jako osobną paczkę !!!!");
                    }
                    else if (liczbareszyZgody > 0 && (liczbaevent > 0 || liczbazgody > 0 || liczbabisnode > 0 || liczbaexito > 0
                        || liczbazgodyZgody > 0 || liczbareszy > 0 || liczbaeventZgody > 0 || liczbabisnodeZgody > 0 || liczbaexitoZgody > 0)) {
                        alert("Mieszasz Paczki, Zgody Resztę można poprać tylko jako osobną paczkę !!!!");
                    }
                    else {
                        downloadCSV();
                    }
                }
            }else{
                downloadCSV();
            }


            function downloadCSV() {
                var system = $('#selectSystem').val();
                var phoneSystem = 1;
                if($('#cellPhoneSystem').length){
                    var phoneSystem = $('#cellPhoneSystem').val();
                }
                document.getElementById("loader").style.display = "block";  // show the loading message.
                $('#pobierz').attr("disabled", true);
                var tablica;
                if (rejonka != '') {
                    szukana = rejonka + '_Rejonka';
                }
                $.ajax({
                    type: "POST",
                    url: '{{ url('storageResearch') }}',
                    data: {
                        "System": system,
                        "phoneSystem" : phoneSystem,
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
                    success: function (response) {
                        var RphoneSystem = parseInt(response['phoneStstem']);
                        var Rsystem = parseInt(response['system']);
                        var RLogId = parseInt(response['RLogId']);
                        const placeToAppend = document.querySelector('#loader');
                        placeToAppend.innerHTML = '';
                        var formDiv = document.createElement('div');
                        formDiv.innerHTML = `
                                <form method="POST" action="{{URL::to('/gererateCSV')}}" id="csvForm">
                                    <input type="hidden" name="Rsystem" value=` + Rsystem + `>
                                    <input type="hidden" name="RphoneSystem" value=` + RphoneSystem + `>
                                    <input type="hidden" name="RLogId" value=` + RLogId + `>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                </form>`;
                        placeToAppend.appendChild(formDiv);
                        var csvForm = document.querySelector('#csvForm');
                        csvForm.submit();
                        $('#pobierz').attr("disabled", false);
                        document.getElementById("loader").style.display = "none";
                        $("#any_button").trigger("click");
                    }
                });
            }


        });
    </script>
@endsection