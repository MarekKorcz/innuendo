@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    <h2>@lang('common.temp_user_boss_entites') :</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>@lang('common.name')</td>
                <td>@lang('common.created_at')</td>
                <td>@lang('common.action')</td>
            </tr>
        </thead>
        <tbody>
            @foreach($tempBosses as $tempBoss)
                <tr>
                    <td>{{$tempBoss->name}} {{$tempBoss->surname}}</td>
                    <td>{{$tempBoss->created_at}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/admin/temp-user/boss/show/' . $tempBoss->id) }}">
                            @lang('common.show')
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>@lang('common.active_bosses') :</h2>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>@lang('common.name')</td>
                <td>@lang('common.is_approved')</td>
                <td>@lang('common.created_at')</td>
                <td>@lang('common.action')</td>
            </tr>
        </thead>
        <tbody>
            @foreach($bosses as $boss)
                <tr>
                    <td>{{$boss->name}} {{$boss->surname}}</td>
                    <td>
                        @if ($boss->isApproved == 1)
                            @lang('common.yes')
                        @else
                            @lang('common.no')
                        @endif
                    </td>
                    <td>{{$boss->created_at}}</td>
                    <td>
                        <a class="btn btn-primary" href="{{ URL::to('/admin/boss/show/' . $boss->id) }}">
                            @lang('common.show')
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection