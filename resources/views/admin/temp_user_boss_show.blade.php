@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <h2>@lang('common.temp_user_boss_values') :</h2>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>@lang('common.name')</td>
                <td>@lang('common.email_address')</td>
                <td>@lang('common.phone_number')</td>
                <td>@lang('common.created_at')</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$tempBoss->name}} {{$tempBoss->surname}}</td>
                <td>{{$tempBoss->email}}</td>
                <td>{{$tempBoss->phone_number}}</td>
                <td>{{$tempBoss->created_at}}</td>
            </tr>
        </tbody>
    </table>

    <h2>@lang('common.temp_user_boss_properties') :</h2>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>@lang('common.name')</td>
                <td>@lang('common.street')</td>
                <td>@lang('common.created_at')</td>
                <td>@lang('common.action')</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$tempProperty->name}}</td>
                <td>{{$tempProperty->street}} {{$tempProperty->street_number}} / {{$tempProperty->house_number}}</td>
                <td>{{$tempProperty->created_at}}</td>
                <td>
                    <a class="btn btn-success" href="{{ URL::to('/temp-property/' . $tempProperty->id) }}">
                        @lang('common.show')
                    </a>
                    <a class="btn btn-primary" href="{{ URL::to('temp-property/' . $tempProperty->id . '/edit') }}">
                        @lang('common.edit')
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection