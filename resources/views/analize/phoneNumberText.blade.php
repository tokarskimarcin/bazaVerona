@extends('main')
@section('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          type="text/css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" type="text/css"/>
@endsection

@section('content')
    <div class="page-header">
        <h1>Generowanie plików sms</h1>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Generowanie plików sms
        </div>
        <div class="panel-body">
            <form method="post" action="{{URL::to('/phoneNumberText')}}" enctype="multipart/form-data">
            <div class="row">
                    <div class="alert alert-info">
                        Pierwszy wiersz w przesyłanym pliku musi zawierać nagłówek("telefon").
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-lg-4">
                        <input id="import_file" name="import_file" placeholder="Wybierz plik..." type="file"/>
                    </div>
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="date">Data i czas</label>
                                    <input id="date" name="date" type="date" style="width: 100%;" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <input id="time" name="time" type="time" style="width: 100%;" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="contents">
                                Treść
                            </label>
                            <textarea name="contents" id="contents" cols="50" rows="3" class="form-control" required></textarea>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <input type="submit" value="Generuj" class="btn btn-success" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection