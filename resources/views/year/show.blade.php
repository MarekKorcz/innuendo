@extends('layouts.app')
@section('content')

{!! Html::style('css/year_show.css') !!}
{!! Html::script('js/year_show.js') !!}

<div class="container">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4">
            @if ($year->property !== null)
                <a class="btn btn-success" href="{{ URL::to('property/' . $year->property->id) }}">
                    @lang('common.back_to_property')
                </a>
            @else
                <a class="btn btn-primary" href="{{ URL::to('/property/index') }}">
                    @lang('common.all_properties')
                </a>
            @endif
        </div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-danger delete" style="color: white;" data-year_id="{{$year->id}}">
                @lang('common.delete')
            </a>
        </div>
    </div>

    <div class="text-center">
        <h2>{{ $year->year }}</h2>
    </div>
    
    <hr>
    
    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>@lang('common.months'):</h2>
        </div>
        @if (count($year->months) > 0)
            <div class="list-group">
                @foreach ($year->months as $month)
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