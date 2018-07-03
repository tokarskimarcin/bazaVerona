@extends('main')
@section('style')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          type="text/css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" type="text/css"/>
@endsection

@section('content')
    <div class="page-header">
        <h1>Blokowanie</h1>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Panel blokowania kodu
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alertBox">

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Nr telefonu</span>
                        <input id="lockInput" type="number" min="0" class="form-control" placeholder="Nr telefonu"
                               aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="col-lg-6">
                    <button id="lock" class="btn btn-default btn-block" data-toggle="modal" data-target=".myModal">
                        Zablokuj
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Historia blokowania
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Baza</span>
                        <input id="baseInput" type="number" min="0" class="form-control" placeholder="Baza"
                               aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Nr telefonu</span>
                        <input id="telInput" type="number" min="0" class="form-control" placeholder="Nr telefonu"
                               aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="input-group">
                        <span class="input-group-addon" id="basic-addon1">Data zablokowania</span>
                        <input id="dateInput" type="date"  class="form-control" placeholder="Data zablokowania"
                               aria-describedby="basic-addon1">
                    </div>
                </div>

            </div>
            <div class="row" style="margin-top: 1em">
                <div class="col-lg-12">
                    <table id="datatable" class="table stripe row-border">
                        <thead>
                        <tr>
                            <th>Baza</th>
                            <th>Telefon</th>
                            <th>Data zablokowania</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="myModal" class="modal fade myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="gridSystemModalLabel">Potwierdzenie</h4>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz zablokować ten kod?
                    <strong><span id="writtenCode">KOD</span></strong>
                </div>
                <div class="modal-footer">
                    <button id="confirm" type="button" class="btn btn-primary" data-dismiss="modal">Tak, chcę zablokować</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" charset="utf8"
            src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
    <script>
        /** --------------------------------- VARIABLES --------------------------------- **/
        let alertBox = $('.alertBox');

        /** --------------------------------- DATATABLE --------------------------------- **/
        let datatable = $("#datatable").DataTable({
            autoWidth: true,
            processing: true,
            serverSide: true,
            scrollY: '40vh',
            scrollCollapse: true,
            order: [[2, 'desc']],
            ajax: {
                url: "{{ route('api.datatableLockAjax') }}",
                type: 'POST',
                data: function(d) {
                    d.id_baza = $('#baseInput').val();
                    d.telefon = $('#telInput').val();
                    d.created_at = $('#dateInput').val();
                },
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
            },
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Polish.json"
            },
            columns: [
                {data: 'id_baza'},
                {data: 'telefon'},
                {data: 'created_at'}
            ]
        });

        /** --------------------------------- EVENT LISTENERS --------------------------------- **/

        $('#baseInput').change(onInputChange);
        $('#telInput').change(onInputChange);
        $('#dateInput').change(onInputChange);

        function onInputChange(e){
            datatable.ajax.reload();
        }

        $('#lock').click((e) => {
            $('#writtenCode').text($('#lockInput').val());
        });

        $('#confirm').click((e) => {
            $.ajax({
                url: "{{ route('api.lockPostAjax') }}",
                type: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                data: {
                    'telefon': $('#lockInput').val()
                },
            }).done(function (response) {
                console.log(response);
                alertBox.text('');
                if (response === 'locked') {
                    alertBox.append('<div class="alert alert-success" role="alert">\n' +
                        '  <strong>Udało się!</strong> Zablokowano numer ' +$('#lockInput').val()+
                        '</div>');
                }
                if(response === 'locked.new'){
                    alertBox.append('<div class="alert alert-success" role="alert">\n' +
                        '  <strong>Udało się!</strong> Zablokowano <strong>NOWY</strong> numer ' +$('#lockInput').val()+
                        '</div>');
                }
                if(response === 'already locked'){
                    alertBox.append('<div class="alert alert-info" role="alert">\n' +
                        '  <strong>Nie zablokowano!</strong> Ten numer został już zablokowany ' +$('#lockInput').val()+
                        '</div>');
                }
                datatable.ajax.reload();
            }).fail(function (response) {
                alertBox.text('');
                alertBox.append('<div class="alert alert-danger" role="alert">\n' +
                    '  <strong>Coś poszło nie tak!</strong> '+
                    '</div>');

            });
        });


    </script>
@endsection