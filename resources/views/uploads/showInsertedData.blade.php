@extends('main')
@section('style')
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.2.1/css/select.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('content')
<h2>Tabela wynikowa</h2>
<hr>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>telefon</th>
                            <th>id bazy</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($dane as $data)
                        <tr class="records">
                            <td>{{$data[0]}}</td>
                            <td @if($data[1]->idbaza == 'bisnode') class="bisnode" @elseif($data[1]->idbaza == 'event') class="event" @elseif($data[1]->idbaza == 'zgody') class="zgody" @elseif($data[1]->idbaza == 'reszta') class="reszta" @endif>{{ $data[1]->idbaza}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th>baza</th>
                        <th>procent calosci</th>
                    </tr>
                    </thead>
                    <tbody id="agregate_body">

                    </tbody>
                </table>
            </div>
        </div>


    </div>

@endsection

@section('script')
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function(e) {
            let records = Array.from(document.getElementsByClassName('records'));
            let bisnode = Array.from(document.getElementsByClassName('bisnode'));
            let event = Array.from(document.getElementsByClassName('event'));
            let zgody = Array.from(document.getElementsByClassName('zgody'));
            let reszta = Array.from(document.getElementsByClassName('reszta'));

            function dataRow(name, txt) {
                if(name.length > 0) {
                    //Create td with text Bisnode
                    let textNode = document.createTextNode(txt);
                    let td_name_node = document.createElement('td');
                    td_name_node.appendChild(textNode);

                    let percent = Math.round(name.length / records.length * 10000) / 100;
                    percent += "%";
                    let percent_node = document.createTextNode(percent);
                    let td_percent_node = document.createElement('td');
                    td_percent_node.appendChild(percent_node);

                    let node = document.createElement('tr');
                    node.appendChild(td_name_node);
                    node.appendChild(td_percent_node);
                    document.getElementById('agregate_body').appendChild(node);
                }
                else {
                    let textNode = document.createTextNode(txt);
                    let td_name_node = document.createElement('td');
                    td_name_node.appendChild(textNode);

                    let percent = 0 + "%";
                    let percent_node = document.createTextNode(percent);
                    let td_percent_node = document.createElement('td');
                    td_percent_node.appendChild(percent_node);

                    let node = document.createElement('tr');
                    node.appendChild(td_name_node);
                    node.appendChild(td_percent_node);
                    document.getElementById('agregate_body').appendChild(node);
                }
            }

            dataRow(bisnode, "bisnode");
            dataRow(event, "event");
            dataRow(zgody, "zgody");
            dataRow(reszta, "reszta");
        });
    </script>
@endsection