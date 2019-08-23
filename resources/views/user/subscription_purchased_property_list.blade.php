@extends('layouts.app')

@section('content')

{!! Html::style('css/subscription_purchased_property_list.css') !!}

<div class="container">    
    <div class="text-center">
        <h1 class="text-center padding">Lista lokalizacji z wykupionymi pakietami</h1>
        <hr>
    </div>
    <div class="wrapper">
        @foreach ($properties as $property)
            <div class="card">
                <div class="card-body">
                    @if ($property->boss_id)
                        <p style="color: blue;">#private property</p>
                    @endif
                    <strong>
                        {{$property->name}}
                    </strong>
                    <p class="card-text">
                        @if ($property->description)
                            {!!$property->description!!}
                        @endif
                        <p>@lang('common.address') : <strong>{{$property->street}} {{$property->street_number}} / {{$property->house_number}} {{$property->city}}</strong></p>
                    </p>
                    <div class="text-center">
                        <a class="btn btn-success" href="{{ URL::to('user/subscription/list/purchased/' . $property->id) }}">
                            @lang('common.show')
                        </a>                                    
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection