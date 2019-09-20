@extends('layouts.app')
@section('content')

{!! Html::style('css/year_show.css') !!}
{!! Html::script('js/year_show.js') !!}

<div class="container">

    <nav class="navbar navbar-inverse">
        <ul class="nav navbar-nav">
            <li>
                @if ($property_id != 0)
                    <a class="btn btn-success" href="{{ URL::to('property/' . $property_id) }}">
                        @lang('common.back_to_property')
                    </a>
                @else
                    <a class="btn btn-primary" href="{{ URL::to('/property/index') }}">
                        @lang('common.all_properties')
                    </a>
                @endif
            </li>
        </ul>
        <div class="navbar-header">
            <a class="btn btn-danger delete" style="color: white;" data-year_id="{{$year->id}}">
                @lang('common.delete')
            </a>
        </div>
    </nav>

    <div class="text-center">
        <h2>{{ $year->year }}</h2>
    </div>
    
    <hr>
    
    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>@lang('common.months') :</h2>
        </div>
        @if (count($months) > 0)
            <div class="list-group">
                @foreach ($months as $month)
                    <a class="list-group-item text-center" href="{{ URL::to('month/show/' . $month->id) }}">
                        <h4>
                            @if (Session('locale') == "en")
                                {{ $month->month_en }}
                            @else
                                {{ $month->month }}
                            @endif
                        </h4>
                    </a>
                @endforeach
            </div>
        @endif
        <div class="text-center" style="padding-top: 50px;">
            <a class="btn btn-success" href="{{ action('MonthController@create', $year->id) }}">
                @lang('common.add_month')
            </a>
        </div>
    </div>
    
    <div id="deleteYear" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.year_delete')</h4>
                <button id="deleteYearCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
</div>
@endsection