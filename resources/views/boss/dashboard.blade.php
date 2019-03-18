@extends('layouts.app')
@section('content')

{!! Html::script('js/boss_dashboard.js') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 30px;">
        <div class="text-center">
            <h2>Ustaw kod rejestracyjny dla pracowników</h2>
            {{ Form::open(['id' => 'register-code', 'action' => 'BossController@setCode', 'method' => 'POST']) }}
            
                <div class="form-group">
                    <label  for="code">Kod rejestracyjny</label>
                    <input id="code" name="code" class="form-control" type="text" value="{{$code ? $code : ''}}">
                    <div id="code-warning"></div>
                    <a id="generateCode" class="btn btn-warning" style="margin: 9px;">Wygeneruj nowy kod</a>
                </div>
            
                {{ Form::submit('Zapisz kod', array('class' => 'btn btn-primary')) }}
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection