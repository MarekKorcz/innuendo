@extends('layouts.app')

@section('content')

{!! Html::style('css/property_list.css') !!}

<div class="container">
    <h1 class="text-center padding-top">@lang('common.your_properties')</h1>
    
    
    <div class="wrapper">
        @foreach ($properties as $property)
            <div class="card">
                <div class="text-center box">
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
                    <a href="{{ URL::to('boss/property/' . $property->id) }}" class="btn btn-success">
                        @lang('common.show')
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection