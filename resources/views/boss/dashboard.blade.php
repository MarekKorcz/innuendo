@extends('layouts.app')
@section('content')

{!! Html::script('js/boss_dashboard.js') !!}

<div class="container">
    
    <div class="jumbotron" style="margin-top: 30px;">
        @if (count($codes))
            @for ($i = 1; $i <= count($codes); $i++)
                <div class="row" style="padding: 12px;">
                    <div class="col-xs-12 col-sm-12 col-lg-12 col-md-12">
                        <div class="card">
                            <div class="text-right">
                                {!!Form::open(['action' => ['BossController@destroyCode', $codes[$i]['code_id']], 'method' => 'POST'])!!}
                                    {{ Form::hidden('_method', 'DELETE') }}
                                    {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                {!!Form::close()!!}
                            </div>
                            <div class="text-center">
                                {{ Form::open(['id' => 'register-code', 'action' => 'BossController@setCode', 'method' => 'POST']) }}
                                    @if ($codes[$i]['properties'])
                                        @foreach ($codes[$i]['properties'] as $property)
                                            <div class="code-items" data-code_id="{{$codes[$i]['code_id']}}" style="padding: 12px 21px 12px 21px;">
                                                <ul class="property">
                                                    @if ($property['chosen_property_id'] != 0)
                                                        <li class="form-control" 
                                                            data-active="true" 
                                                            data-property_id="{{$property['property_id']}}" 
                                                            data-chosen_property_id="{{$property['chosen_property_id']}}" 
                                                            style="background-color: lightskyblue;"
                                                        >
                                                            {{$property['property_name']}}
                                                        </li>
                                                    @else
                                                        <li class="form-control" 
                                                            data-active="false" 
                                                            data-property_id="{{$property['property_id']}}" 
                                                            data-chosen_property_id="{{$property['chosen_property_id']}}"
                                                        >
                                                            {{$property['property_name']}}
                                                        </li>
                                                    @endif
                                                </ul>             
                                                <ul class="subscriptions" data-chosen_property_id="{{$property['chosen_property_id']}}">
                                                    @foreach ($property['subscriptions'] as $subscription)
                                                        @if ($subscription['isChosen'])
                                                            <li class="form-control" 
                                                                data-subscription_id="{{$subscription['subscription_id']}}" 
                                                                data-active="true" 
                                                                style="background-color: lightgreen;"
                                                            >
                                                                {{$subscription['subscription_name']}}
                                                            </li>
                                                        @else
                                                            <li class="form-control" 
                                                                data-subscription_id="{{$subscription['subscription_id']}}" 
                                                                data-active="false"
                                                            >
                                                                {{$subscription['subscription_name']}}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @endif

                                    <div style="padding: 0 12px 12px 12px;">
                                        <input name="code_id" type="hidden" value="{{$codes[$i]['code_id']}}">

                                        @if ($codes[$i]['code'])
                                            <p>
                                                Przy rejestracji pracownicy muszą wpisać poniższy kod
                                            </p>
                                            <p>
                                                Kod do rejestracji:
                                                <input class="code-text" name="code-text" type="text" value="{{$codes[$i]['code']}}" style="margin: 0px 12px 0px 12px;">
                                                <a class="btn btn-info copy-button">
                                                    Kopjuj kod
                                                </a>
                                            </p>

                                            <input name="code" type="hidden" value="false">
                                            {{ Form::submit('Wyłącz rejestracje', array('class' => 'btn btn-warning')) }}
                                        @else
                                            <input name="code" type="hidden" value="true">
                                            {{ Form::submit('Włącz rejestracje', array('class' => 'btn btn-success')) }}
                                        @endif
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        @endif
        
        <div class="col-lg-12 text-center" style="margin-bottom: 30px;">
            <a class="btn btn-success" href="{{ action('BossController@addCode') }}">
                Dodaj nowy kod
            </a>
        </div>
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
                            <a class="btn btn-success btn-lg" href="{{ URL::to('boss/subscription/list/') }}">
                                Pokaż
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection