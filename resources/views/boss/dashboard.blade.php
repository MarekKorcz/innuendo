@extends('layouts.app')
@section('content')

{!! Html::script('js/boss_dashboard.js') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 30px;">
        <div class="text-center">
            <h2>Rejestracja pracowników</h2>
            {{ Form::open(['id' => 'register-code', 'action' => 'BossController@setCode', 'method' => 'POST']) }}
            
                @if ($code)
                    <p>
                        Przy rejestracji pracownicy muszą wpisać poniższy kod!
                    </p>
                    <p>
                        Kod do rejestracji:
                        <input id="code-text" type="text" value="{{$code}}" style="margin: 0px 12px 0px 12px;">
                        <a id="copy-button" class="btn btn-info">
                            Kopjuj kod!
                        </a>
                    </p>
                    
                    <input id="code" name="code" type="hidden" value="false">
                    {{ Form::submit('Wyłącz rejestracje', array('class' => 'btn btn-warning')) }}
                @else
                    <input id="code" name="code" type="hidden" value="true">
                    {{ Form::submit('Włącz rejestracje', array('class' => 'btn btn-success')) }}
                @endif
            
                
            {{ Form::close() }}
        </div>
    </div>
</div>
@endsection