@extends('layouts.app')
@section('content')

{!! Html::script('js/boss_dashboard.js') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 30px;">
        @foreach ($codes as $code)
            <div class="text-center">
                {{ Form::open(['id' => 'register-code', 'action' => 'BossController@setCode', 'method' => 'POST']) }}

                    
                
                    @if ($code->code)
                        <p>
                            Przy rejestracji pracownicy muszą wpisać poniższy kod!
                        </p>
                        <p>
                            Kod do rejestracji:
                            <input id="code-text" type="text" data-code_id="{{$code->code_id}}" value="{{$code->code}}" style="margin: 0px 12px 0px 12px;">
                            <a class="btn btn-info copy-button">
                                Kopjuj kod!
                            </a>
                        </p>

                        <input name="code" type="hidden" value="false">
                        {{ Form::submit('Wyłącz rejestracje', array('class' => 'btn btn-warning')) }}
                    @else
                        <input name="code" type="hidden" value="true">
                        {{ Form::submit('Włącz rejestracje', array('class' => 'btn btn-success')) }}
                    @endif


                {{ Form::close() }}
            </div>
        @foreach
    </div>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Twoje lokalizacje</h4>
                        <p class="card-text text-center">
                            Widok Twoich lokalizacji z listą pracowników do nich przynależących
                        </p>
                        <div class="text-center">
                            <a class="btn btn-success btn-lg" href="{{ URL::to('boss/property/list/') }}">
                                Pokaż
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Twoje wykupione subskrypcje</h4>
                        <p class="card-text text-center">
                            Widok wykupionych subskrypcji ze szczegółowymi informacjami ich dotyczącymi oraz listą pracowników z nich korzystających
                        </p>
                        <div class="text-center">
<!--                            <a class="btn btn-success btn-lg" href="{{ URL::to('boss/dashboard/') }}">
                                Pokaż
                            </a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection