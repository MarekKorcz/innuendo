@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            {!!Form::open(['action' => ['YearController@destroy', $year->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{ Form::hidden('_method', 'DELETE') }}
                <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
            {!!Form::close()!!}
        </div>
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
    </nav>

    <h2 style="padding: 20px;">{{ $year->year }}</h2>
    
    <hr>
    
    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>@lang('common.values') :</h2>
        </div>
        <table class="table table-striped">
            <tr>
                <th>@lang('common.year') :</th>
                <th>@lang('common.created_at') :</th>
                <th>@lang('common.updated_at') :</th>
                <th>@lang('common.property_id') :</th>
            </tr>
            <tr>
                <td>{{ $year->year }}</td>
                <td>{{ $year->created_at }}</td>
                <td>{{ $year->updated_at }}</td>
                <td>{{ $property_id }}</td>
            </tr>
        </table>
    </div>
    
    <div class="jumbotron">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2>@lang('common.months') :</h2>
        </div>
        @if (count($months) > 0)
            <div class="list-group">
                @foreach ($months as $month)
                    <a class="list-group-item text-center" href="{{ URL::to('month/show/' . $month->id) }}">
                        <h4>{{$month->month}}</h4>
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
</div>
@endsection