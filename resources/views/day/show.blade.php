@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li>
                @if ($month)
                    <a class="btn btn-success" href="{{ URL::to('month/show/' . $month->id) }}">
                        @lang('common.back_to_month')
                    </a>
                @endif
            </li>
        </ul>
    </nav>

    <h2 style="padding: 20px;">@lang('common.day') {{ $day->day_number }}</h2>
    
    <div class="jumbotron">
        @if (count($graphic) > 0)
            <table class="table table-striped">
                <tr>
                    <th style="width: 16.66%">@lang('common.hours')</th>
                    <th class="text-center">@lang('common.appointments')</th>
                </tr>
                <div class="list-group">
                    <tr>
                        @for ($i = 0; $i < count($graphic); $i++)
                            <tr>
                                <td>
                                    {{$graphic[$i][0]}}
                                </td>
                                <td style="height: 120px;">
                                    <div class="text-center">
                                        {{$graphic[$i][1]}}
                                    </div>
                                </td>
                            </tr>
                        @endfor
                    </tr>
                </div>
            </table>
        @endif
        
        <div class="text-center" style="padding-top: 50px;">
            <a class="btn btn-success" href="{{ action('GraphicController@create', $day->id) }}">
                @lang('common.create_graphic')
            </a>
        </div>
    </div>
</div>
@endsection