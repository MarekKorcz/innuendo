@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            {!!Form::open(['action' => ['YearController@destroy', $year->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                {{ Form::hidden('_method', 'DELETE') }}
                {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
            {!!Form::close()!!}
        </div>
        <ul class="nav navbar-nav">
            <li>
                @if ($property_id != 0)
                    <a class="btn btn-success" href="{{ URL::to('property/' . $property_id) }}">
                        Back to Property
                    </a>
                @else
                    <a class="btn btn-primary" href="{{ URL::to('/property/index') }}">
                        View All Properties
                    </a>
                @endif
            </li>
        </ul>
    </nav>

    <h2>Showing year - {{ $year->year }}</h2>
    
    
    
    
    <!--Place to display months and 'add Month button'-->

    
    
    
</div>
@endsection