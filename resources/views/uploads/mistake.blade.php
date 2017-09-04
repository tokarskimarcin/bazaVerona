@extends('main')
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection






@section('content')
<h2>Import Nowej Bazy "Pomyłki" w formacie CSV/Excel</h2>
<hr>
    <div class="col-xs-12">
        <form class="form-horizontal" method="post" action="wgrajPomylki" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="typ" value="pomylka">
            <div>
                <div class="col-xs-6">
                    <input type="file" name="import_file" />
                    <br />
                     <button type="submit" id="submit" class="btn btn-primary" name="submit" data-loading-text="Loading...">Wgraj Dane</button>
                    <hr>
                </div>
            </div>
        </form>
    </div>

@endsection





@section('script')
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
@endsection