@extends('layouts.app')
@section('content')

{!! Html::style('css/month_show.css') !!}
{!! Html::script('js/month_show.js') !!}

<div class="container">
    
    <div class="row text-center" style="padding-top: 2rem;">
        <div class="col-4">
            @if ($year)
                <a class="btn btn-success" href="{{ URL::to('year/show/' . $year->id) }}">
                    @lang('common.back_to_year')
                </a>
            @endif
        </div>
        <div class="col-4"></div>
        <div class="col-4">
            <a class="btn btn-danger delete" style="color: white;" data-month_id="{{$month->id}}">
                @lang('common.delete')
            </a>
        </div>
    </div>

    <div class="text-center">
        <h2 style="padding: 20px;">
            @if (Session('locale') == "en")
                {{ $month->month_en }}
            @else
                {{ $month->month }}
            @endif
        </h2>
    </div>
    
    <div class="jumbotron">
        @if (count($days) > 0)
            <table class="table table-striped">
                <tr>
                    <th>@lang('common.monday')</th>
                    <th>@lang('common.tuesday')</th>
                    <th>@lang('common.wednesday')</th>
                    <th>@lang('common.thursday')</th>
                    <th>@lang('common.friday')</th>
                    <th>@lang('common.saturday')</th>
                    <th>@lang('common.sunday')</th>
                </tr>
                <div class="list-group">
                    @for ($i = 0; $i < count($days); $i++)
                        @if (($i + 1) == 1 || ($i + 1) == 8 || ($i + 1) == 15 || ($i + 1) == 22 || ($i + 1) == 29)
                            <tr>
                                <td>
                                    @if (is_object($days[$i]) && $days[$i] !== null)
                                        <a class="list-group-item text-center" href="{{ URL::to('day/show/' . $days[$i]->id) }}">
                                            <h4>{{$days[$i]->day_number}}</h4>
                                        </a>
                                    @endif
                                </td>
                        @elseif (($i + 1) == 7 || ($i + 1) == 14 || ($i + 1) == 21 || ($i + 1) == 28 || ($i + 1) == 35)
                                <td>
                                    @if (is_object($days[$i]) && $days[$i] !== null)
                                        <a class="list-group-item text-center" href="{{ URL::to('day/show/' . $days[$i]->id) }}">
                                            <h4>{{$days[$i]->day_number}}</h4>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @else
                            <td>
                                @if (is_object($days[$i]) && $days[$i] !== null)
                                    <a class="list-group-item text-center" href="{{ URL::to('day/show/' . $days[$i]->id) }}">
                                        <h4>{{$days[$i]->day_number}}</h4>
                                    </a>
                                @endif
                            </td>
                        @endif

                        @if (($i + 1) == count($days))
                            </tr>
                        @endif
                    @endfor
                </div>
            </table>
        @endif
        <div class="text-center" style="padding-top: 50px;">
            <a class="btn btn-success" href="{{ action('DayController@create', $month->id) }}">
                @lang('common.add_day')
            </a>
        </div>
    </div>
    
    <div id="deleteMonth" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.month_delete')</h4>
                <button id="deleteMonthCloseButton" class="close" data-dismiss="modal">×</button>
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