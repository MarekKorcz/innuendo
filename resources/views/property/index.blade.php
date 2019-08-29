@extends('layouts.app')
@section('content')
<div class="container">

    <nav class="navbar navbar-inverse">
        <div class="navbar-header"></div>
        <ul class="nav navbar-nav">
            <li style="padding: 5px;">
                <a href="{{ URL::to('property/create') }}" class="btn btn-primary">
                    @lang('common.create_property')
                </a>
            </li>
        </ul>
    </nav>

    <h1>@lang('common.created_properties')</h1>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>@lang('common.name')</td>
                <td>@lang('common.email_address')</td>
                <td>@lang('common.phone_number')</td>
                <td>@lang('common.street')</td>
                <td>@lang('common.city')</td>
                <td>@lang('common.can_show')</td>
                <td>@lang('common.owner')</td>
                <td>@lang('common.action')</td>
            </tr>
        </thead>
        <tbody>
            @foreach($properties as $key => $value)
            <tr>
                <td>{{ $value->name }}</td>
                <td>{{ $value->email }}</td>
                <td>{{ $value->phone_number }}</td>
                <td>{{ $value->street }}</td>
                <td>{{ $value->city }}</td>
                <td>
                    @if ($value->canShow == 0)
                        @lang('common.no')
                    @else
                        @lang('common.yes')
                    @endif
                </td>
                @if ($value->boss_id > 0)
                    <td>{{ $value->boss->name }} {{ $value->boss->surname }}</td>
                @else
                    <td>@lang('common.public')</td>
                @endif
                <td>
                    <a class="btn btn-small btn-success" href="{{ URL::to('property/' . $value->id) }}" style="margin-bottom: 5px;">
                        @lang('common.show')
                    </a>
                    <a class="btn btn-small btn-info" href="{{ URL::to('property/' . $value->id . '/edit') }}" style="margin-bottom: 5px;">
                        @lang('common.edit')
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <h1>@lang('common.temporary_properties')</h1>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <td>@lang('common.name')</td>
                <td>@lang('common.email_address')</td>
                <td>@lang('common.phone_number')</td>
                <td>@lang('common.street')</td>
                <td>@lang('common.city')</td>
                <td>@lang('common.owner')</td>
                <td>@lang('common.action')</td>
            </tr>
        </thead>
        <tbody>
            @foreach($tempProperties as $key => $value)
            <tr>
                <td>{{ $value->name }}</td>
                <td>{{ $value->email }}</td>
                <td>{{ $value->phone_number }}</td>
                <td>{{ $value->street }}</td>
                <td>{{ $value->city }}</td>
                @if ($value->temp_user_id > 0)
                    <td>{{ $value->owner->name }} {{ $value->owner->surname }}</td>
                @else
                    <td>@lang('common.none')</td>
                @endif
                <td>
                    <a class="btn btn-small btn-success" href="{{ URL::to('temp-property/' . $value->id) }}" style="margin-bottom: 5px;">
                        @lang('common.show')
                    </a>
                    <a class="btn btn-small btn-info" href="{{ URL::to('temp-property/' . $value->id . '/edit') }}" style="margin-bottom: 5px;">
                        @lang('common.edit')
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection