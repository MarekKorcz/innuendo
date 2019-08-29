@extends('layouts.app')

@section('content')

{!! Html::style('css/property_list.css') !!}

<div class="container">
    <h1 class="text-center">@lang('common.pick_subscription_from_property')</h1>
    <div id="properties" class="wrapper">
        @foreach ($properties as $property)
            @if ($property->boss_id)
                <div class="text-center box card" style="background-color: lightgreen;">
            @else
                <div class="text-center box card">
            @endif
                <div class="card-body">
                    <p>
                        <strong>
                            {{$property->name}}
                        </strong>
                    </p>
                    @if ($property->description)
                        {!!$property->description!!}
                    @endif
                    <p>
                        @lang('common.address') : 
                        <strong>
                            {{$property->street}} 
                            {{$property->street_number}} / 
                            {{$property->house_number}} 
                            {{$property->city}}
                        </strong>
                    </p>
                    <a href="{{ URL::to('user/property/subscription/list/' . $property->id) }}" class="btn btn-success">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection