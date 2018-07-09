@extends('main')
@section('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          type="text/css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" type="text/css"/>
@endsection

@section('content')
    <div class="page-header">
        <h1>Sprawdź kody pocztowe</h1>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Panel wybierania pliku z numerami telefonów
        </div>
        <div class="panel-body">
            <div class="row">
                <form method="post" action="postPhonenumberZipCodes" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-lg-6">
                        <input id="import_file" name="import_file" placeholder="Wybierz plik..." type="file"/>
                    </div>
                    <div class="col-lg-6">
                        <button id='analize' type="submit" class="btn btn-block btn-info">Analizuj</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel numerów telefonów i kodów pocztowych
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <table class="table" id="datatable">
                        <thead>
                            <tr>
                                <th>Telefon</th>
                                <th>Kod pocztowy</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(Session::has('records'))
                                @foreach(Session::get('records') as $record)
                                    <tr>
                                        <td>{{$record->telefon}}</td>
                                        <td>{{$record->kodpocztowy}}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection