<div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
    <div class="card">
        <div class="text-center">
            {{ Form::open(['id' => 'register-code', 'action' => 'BossController@setCode', 'method' => 'POST']) }}

                @if ($codes[$i]['properties'])
                    @foreach ($codes[$i]['properties'] as $property)
                        <h4>{{$property['property_name']}}</h4>                            
                        <ul class="subscriptions">
                            @foreach ($property['subscriptions'] as $subscription)
                                @if ($subscription['isChosen'])
                                    <li class="form-control" value="{{$subscription['subscription_id']}}" data-active="true" style="background-color: lightgreen;">
                                        {{$subscription['subscription_name']}}
                                    </li>
                                @else
                                    <li class="form-control" value="{{$subscription['subscription_id']}}">
                                        {{$subscription['subscription_name']}}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endforeach
                @endif

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


            {{ Form::close() }}
        </div>
    </div>
</div>