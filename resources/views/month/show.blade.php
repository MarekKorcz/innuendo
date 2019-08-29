@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            {!!Form::open(['action' => ['MonthController@destroy', $month->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{ Form::hidden('_method', 'DELETE') }}
                <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
            {!!Form::close()!!}
        </div>
        <ul class="nav navbar-nav">
            <li>
                @if ($year)
                    <a class="btn btn-success" href="{{ URL::to('year/show/' . $year->id) }}">
                        @lang('common.back_to_year')
                    </a>
                @endif
            </li>
        </ul>
    </nav>

    <h2 style="padding: 20px;">{{ $month->month }}</h2>
    
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
                                    @if (count($days[$i]) > 0)
                                        <a class="list-group-item text-center" href="{{ URL::to('day/show/' . $days[$i]->id) }}">
                                            <h4>{{$days[$i]->day_number}}</h4>
                                        </a>
                                    @endif
                                </td>
                        @elseif (($i + 1) == 7 || ($i + 1) == 14 || ($i + 1) == 21 || ($i + 1) == 28 || ($i + 1) == 35)
                                <td>
                                    @if (count($days[$i]) > 0)
                                        <a class="list-group-item text-center" href="{{ URL::to('day/show/' . $days[$i]->id) }}">
                                            <h4>{{$days[$i]->day_number}}</h4>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @else
                            <td>
                                @if (count($days[$i]) > 0)
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
</div>
@endsection