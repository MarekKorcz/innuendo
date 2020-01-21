@extends('layouts.app')
@section('content')

{!! Html::style('css/property_show.css') !!}
{!! Html::script('js/property_show.js') !!}

<div class="container" style="padding-top: 2rem;">
    
    <div class="jumbotron">
        <div class="row text-center" style="padding-bottom: 2rem;">
            <div class="col-4">
                <a class="btn pallet-2-2 delete-property" style="color: white;" data-property_id="{{$property->id}}">
                    @lang('common.delete')
                </a>
            </div>
            <div class="col-4">
                @if ($property->canShow == 0)
                    <a class="btn pallet-1-3" style="margin: 9px; color: white;" href="{{ URL::to('property/can-show/change/' . $property->id) }}">
                        @lang('common.show_publicly')
                    </a>
                @else
                    <a class="btn pallet-2-4" style="margin: 9px; color: white;" href="{{ URL::to('property/can-show/change/' . $property->id) }}">
                        @lang('common.do_not_show_publicly')
                    </a>
                @endif
            </div>
            <div class="col-4">
                <a class="btn pallet-2-4" style="color: white;" href="{{ URL::to('property/' . $property->id . '/edit') }}">
                    @lang('common.edit')
                </a>
            </div>
        </div>
        <div class="text-center">
            <h1 style="padding-bottom: 1rem;">@lang('common.property_values'):</h1>
            <p>
                @lang('common.owner'):
                <strong style="font-size: 21px;">
                    @if ($property->boss !== null)
                        {{ $property->boss->name }} {{ $property->boss->surname }}
                    @else
                        @lang('common.public')
                    @endif
                </strong>
            </p>
            <p>
                @lang('common.label'):
                <strong style="font-size: 21px;">
                    {{$property->name}}
                </strong>
            </p>
            <p>
                <strong>
                    {!! $property->description !!}
                </strong>
            </p>
            <p>
                @lang('common.address'): 
                <strong>
                    {{$property->street}} 
                    {{$property->street_number}} / 
                    {{$property->house_number}} 
                    {{$property->city}}
                </strong>
            </p>
        </div>
    </div>
        
    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h3>@lang('common.years'):</h3>
        </div>
        <div class="list-group">
            @if (count($property->years) > 0)
                @foreach ($property->years as $year)
                    <a class="list-group-item text-center" href="{{ URL::to('year/show/' . $year->id) }}">
                        <h4>{{$year->year}}</h4>
                    </a>
                @endforeach
            @endif
        </div>
        <div class="text-center" style="padding-top: 33px;">
            <a class="btn pallet-2-3" style="color: white;" href="{{ action('YearController@create', $property->id) }}">
                @lang('common.add_year')
            </a>
        </div>
    </div>
    
    
    <div id="deleteProperty" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.property_delete')</h4>
                <button id="deletePropertyCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn pallet-2-2" style="color: white;">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
    
</div>
@endsection