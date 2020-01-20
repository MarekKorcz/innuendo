@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <div style="padding: 1rem 0 1rem 0">
        
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <h2 class="text-center" style="padding-bottom: 1rem;">@lang('common.active_bosses'):</h2>

                @if (count($bosses) > 0)
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
                                        @if ($boss->is_approved == 1)
                                            @lang('common.yes')
                                        @else
                                            @lang('common.no')
                                        @endif
                                    </td>
                                    <td>{{$boss->created_at}}</td>
                                    <td>
                                        <a class="btn pallet-2-4" style="color: white;" href="{{ URL::to('/admin/boss/show/' . $boss->id) }}">
                                            @lang('common.show')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center">
                        <h3>@lang('common.no_bosses_description')</h3>
                    </div>
                @endif

                <h2 class="text-center" style="padding: 1rem 0 1rem 0;">@lang('common.temp_user_boss_entites'):</h2>

                @if (count($tempBosses) > 0)
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
                                        <a class="btn pallet-2-4" style="color: white;" href="{{ URL::to('/admin/temp-user/boss/show/' . $tempBoss->id) }}">
                                            @lang('common.show')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center">
                        <h3>@lang('common.no_temp_bosses_description')</h3>
                    </div>
                @endif
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection