@extends('layouts.app')
@section('content')
<div class="container">

    <div style="padding: 2rem 0 2rem 0">
        <nav class="navbar navbar-inverse">
            <div class="navbar-header"></div>
            <ul class="nav navbar-nav">
                <li style="padding: 5px;">
                    <a href="{{ URL::to('property/create') }}" class="btn pallet-1-3" style="color: white;">
                        @lang('common.create_property')
                    </a>
                </li>
            </ul>
        </nav>

        <h1 class="text-center" style="padding-bottom: 1rem;">@lang('common.created_properties')</h1>

        @if (count($properties) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td>@lang('common.name')</td>
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
                            <a class="btn btn-small pallet-1-3" style="color: white; margin-bottom: 5px;" href="{{ URL::to('property/' . $value->id) }}">
                                @lang('common.show')
                            </a>
                            <a class="btn btn-small pallet-2-1" style="color: white; margin-bottom: 5px;" href="{{ URL::to('property/' . $value->id . '/edit') }}">
                                @lang('common.edit')
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_properties_description')</h3>
            </div>
        @endif

        <h1 class="text-center" style="padding: 1rem 0 1rem 0;">@lang('common.temporary_properties')</h1>

        @if (count($tempProperties) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <td>@lang('common.name')</td>
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
                        <td>{{ $value->street }}</td>
                        <td>{{ $value->city }}</td>
                        @if ($value->temp_user_id > 0)
                            <td>{{ $value->owner->name }} {{ $value->owner->surname }}</td>
                        @else
                            <td>@lang('common.none')</td>
                        @endif
                        <td>
                            <a class="btn btn-small pallet-1-3" style="color: white; margin-bottom: 5px;" href="{{ URL::to('temp-property/' . $value->id) }}">
                                @lang('common.show')
                            </a>
                            <a class="btn btn-small pallet-2-1" style="color: white; margin-bottom: 5px;" href="{{ URL::to('temp-property/' . $value->id . '/edit') }}">
                                @lang('common.edit')
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_temp_properties_description')</h3>
            </div>
        @endif
    </div>
</div>
@endsection