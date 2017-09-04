@extends('main')


@section('content')
    <?php if(isset($_POST['imie']))
        {
           echo "Formularz zostal wysłany";
        }?>
    <h1>Wniosek o nadanie numeru Pesel</h1>
    <hr>
    <p>Instrukcja wypełniania w trzech krokach</p>
        <div class="form-group">
                <label>1.</label> <input type="text"  placeholder="Tu wpisuj dane" class="form-control" id="usr">
            <p>2. Pole Wyboru zaznaczaj  <label><input type="checkbox" value=""></label> lub  <label><input type="checkbox" value=""></label></p>
            <p>3. Wypełnij kolorem czarnum lub niebieskim</p>
            <p>Przykład wypełnionego wniosku znajdziesz na stronie internetowej prowadzonej

                przez Ministerstwo Spraw Wewnętrznych.</p>
        </div>
    <hr>
    <div class="form-group">

        <h1>1. Wniskodawca</h1>
        <label>Imie</label>
        <input type="text" class="form-control" name="imie" method="post">

        <label>Nazwisko</label>
        <input type="text" class="form-control" name="Nazwisko">

        <p>Adres do korespondencji osboty, ktora składa wnioske</p>
        <label>Ulica</label>
        <input type="text" class="form-control" name="ulica">

        <label>Numer domu</label>
        <input type="text" class="form-control" name="nrdomu">

        <label>Numer Lokalu</label>
        <input type="text" class="form-control" name="nrlokalu">

        <label>Kod pocztowy</label>
        <input type="text" class="form-control" name="kodpocztowy">

        <label>Miejscowosc</label>
        <input type="text" class="form-control" name="miejscowosc">
    </div>

    <hr>

    <div class="form-group">
        <h1>2. Dane osoby, której dotyczy wniosek</h1>
        <label>Imie pierwsze</label>
        <input type="text" class="form-control" name="pierwszeimie">

        <label>Imie drugie</label>
        <input type="text" class="form-control" name="drugieimie">


        <label>Imiona kolejne</label>
        <input type="text" class="form-control" name="imionakolejne">

        <p>Płeć</p>
            <label><input type="checkbox" value="k">kobieta</label>
            <label><input type="checkbox" value="m">mężczyzna</label></p>


        <label>Data Urodzenia</label>
        <input type="date" class="form-control" name="dataurodzenia">

        <label>Kraj urodzenia</label>
        <input type="text" class="form-control" name="krajurodzenia">


        <label>Kraj miejsca zamieszkania</label>
        <input type="text" class="form-control" name="miejscezam">

        <p>Obywatelstwo lub status bezpaństwowca</p>
        <label><input type="checkbox" value="PL">polskie</label>
        <label><input type="checkbox" value="B">bezpaństwowiec</label>
        <label><input type="checkbox" value="I">inne</label><input type="text" class="form-control">
        <p>Ostatnio wydany paszport obywatela polskieg</p>

        <label>Seria i numer</label>
        <input type="text" class="form-control" name="seriainumerpasz">


        <label>Data ważności paszportu</label>
        <input type="date" class="form-control" name="datawazpasz">

        <p>Dokument podróży cudzoziemca lub inny dokument potwierdzający tożsamość i obywatelstwo</p>

        <label>Seria i numer</label>
        <input type="text" class="form-control" name="seriainumerpodroza">


        <label>Data ważności paszportu</label>
        <input type="date" class="form-control" name="datawazpodroza">
    </div>
    <hr>
    <div class="form-group">
        <h1>3. Dodatkowe dane osoby, której wniosek dotyczy, oraz dane jej rodzicow</h1>
        <p> Wypełnij, jeżeli dane są dostępne i wynikają z przedstawionych odkumentów</p>

        <label>Nazwisko rodowe</label>
        <input type="text" class="form-control" name="rodowenazwisko">


        <label>Miejsce urodzenia(nazwa miejscowości)</label>
        <input type="text" class="form-control" name="miejsceuroadzeniamatki">

        <label>Oznaczenie aktu urodzenia</label>
        <input type="text" class="form-control" name="oznaczeniematki">

        <label>Oznaczenie urzędu stanu cywilnego, w którym został sporządzony akt urodzenia</label>
        <input type="text" class="form-control" name="stancywilnymatki">

        <label>Imie ojca pierwsze</label>
        <input type="text" class="form-control" name="imieojca">


        <label>Nazwisko rodowe Ojca</label>
        <input type="text" class="form-control" name="nazwiskoojca">


        <label>Imie matki pierwsze</label>
        <input type="text" class="form-control" name="imiematkipierwsze">


        <label>Nazwisko rodowe Matki</label>
        <input type="text" class="form-control" name="nazwiskomatki">


        <p> Ostatnio wydany dowód osobisty obywatela polskiego</p>
        <label>Seria i numer</label>
        <input type="text" class="form-control" name="numerdowoduosobistego">

        <label>Data ważności dowodu osobistego</label>
        <input type="date" class="form-control" name="datawaznoscidowodu">

        <label>Oznaczenie organu, który wydał dowód osobisty</label>
        <input type="text" class="form-control" name="organdowodu">
    </div>
    <hr>
    <div class="form-group">
     <h1>4. Dane o stanie cywilnym osoby, której wniosek dotyczy</h1>

        <p>Stan Cywilny>
        <label><input type="checkbox" value="S">kawaler/panna</label>
        <label><input type="checkbox" value="Z">żonaty/zamężna</label></p>


        <label>Imie małżonka</label>
        <input type="text" class="form-control" name="imiemazlonka">



        <label>Nazwisko rodowe małżonka</label>
        <input type="text" class="form-control" name="nazwiskomalzonka">


        <label>Numer pesel małżonka</label>
        <input type="text" class="form-control">
            <label><input type="checkbox" value="Roz">rozwiedziony/rozwiedziona</label>
            <label><input type="checkbox" value="wdo">wdowiec/wdowa</label></p>

    </div>
    <hr>

    <div class="form-group">
    <h1>5. Ostatnie zdarzenia mające wpływ na małżeństwo</h1>
            <p>Wypełnij, jeśli osoba, której dotyczy wniosek, kiedykolwiek zawarła związek małżeński. Zaznacz

                tylko jedno, najbardziej aktualne zdarzenie i uzupełnij pozostałe pola.</p>

        <label>Zdarzenie:</label>
        <label><input type="checkbox" value="1">zawarcie związku małżeńskiego</label>
        <label><input type="checkbox" value="2">rozwiązanie związku małżeńskiego</label>
        <label><input type="checkbox" value="3">unieważnienie związku małżeńskiego</label>
        <label><input type="checkbox" value="4">zgon małżonka (zaznacz jeśli znasz datę zgonu)</label>
        <label><input type="checkbox" value="5">zgon małżonka - znalezienie zwłok (zaznacz jeśli małżonek zmarł ale znasz jedynie datę znalezienie ciała)</label>

        <label>Data zdarzenia</label>
        <input type="date" class="form-control" name="datazdarzenia">

        <p> Oznaczenie aktu małżeństwa albo sygnatury akt sądu, który rozwiązał/ unieważnił  małżeństwo, albo numer aktu zgonu małżonka</p>
        <input type="text" class="form-control" name="aktrozwodu">

        <p> Oznaczenie urzędu stanu cywilnego, w którym sporządzono akt małżeństwa

            albo akt zgonu, albo oznaczenie sądu, który rozwiązał/unieważnił małżeństwo</p>
        <input type="text" class="form-control" name="aktzgodnu">
    </div>
    <hr>
    <div class="form-group">
        <h1>6. Forma przekazania wnioskodawcy powiadomnienia o nadaniu numeru PESEL</h1>
        <label><input type="checkbox" value="pisemna">pisemna</label>
        <label><input type="checkbox" value="elektroniczna">dokument elektroniczny</label>
        <p>Wypełnij jeśli zaznaczyłeś opcje "dokumnet elektroniczny"</p>


        <label>Adres elektroniczny</label>
        <input type="text" class="form-control" name="adreselektroniczny">
    </div>
    <hr>
    <div class="form-group">
        <h1>7.Podstawa prawna upoważniająca do otrzymania numeru PESEL – wskazanie przepisu, z którego wynika

            obowiązek posiadania numeru PESEL </h1>
        <input type="text" class="form-control" name="podstawaprawna">
    </div>
    <hr>
    <div class="form-group">
        <h1>8. Podpis</h1>
        <label>Miejscowość</label>
        <input type="text" class="form-control" name="miejscepodpisu">
        <label>Data</label>
        <input type="date" class="form-control" name="datapodpisu">
        <label>Własnoręczny podpis wnioskodawcy</label>
        <input type="text" class="form-control" name="wlasnorecznypodpis">
    </div>
    <a href="form" class="btn btn-primary">Wyślij Formularz</a>
@endsection