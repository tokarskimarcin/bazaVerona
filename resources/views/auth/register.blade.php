@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Imie</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('last') ? ' has-error' : '' }}">
                            <label for="last" class="col-md-4 control-label">Nazwisko</label>

                            <div class="col-md-6">
                                <input id="last" type="text" class="form-control" name="last" value="{{ old('last') }}" required autofocus>

                                @if ($errors->has('last'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('rodzaj') ? ' has-error' : '' }}">
                            <label for="rodzaj" class="col-md-4 control-label">Rodzaj Bazy</label>
                            <div class="col-md-6">
                                <select name="rodzaj">
                                    <option value="Badania">Badania</option>
                                    <option value="Wysyłka">Wysyłka</option>
                                    <option value="Badania/Wysyłka">Badania/Wysyłka</option>
                                </select>
                                @if ($errors->has('b/w'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('rodzaj') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('dep_id') ? ' has-error' : '' }}">
                            <label for="dep_id" class="col-md-4 control-label">Oddział</label>
                            <div class="col-md-6">
                                <select name="dep_id">

                                    <option value="2">Radom</option>
                                    <option value="3">Lublin</option>
                                    <option value="4">Skarżysko-Kamienna</option>
                                    <option value="5">Ostrowiec Świętokrzyski</option>
                                    <option value="6">Łódź</option>
                                    <option value="7">Białystok</option>
                                    <option value="8">Poznań</option>
                                    <option value="9">Starachowice</option>
                                    <option value="10">Bialystok</option>
                                    <option value="11">Chełm</option>

                                </select>
                                @if ($errors->has('dep_id'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dep_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Hasło</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Potwierdź Hasło</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                        Dodaj uzytkownika
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
