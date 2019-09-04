@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <div style="padding: 1rem 0 1rem 0">
        
        <h2 class="text-center" style="padding-bottom: 1rem;">@lang('common.active_users') :</h2>

        @if (count($users) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.name')</td>
                        <td>@lang('common.created_at')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}} {{$user->surname}}</td>
                            <td>{{$user->created_at}}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ URL::to('/admin/user/show/' . $user->id) }}">
                                    @lang('common.show')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_users_description')</h3>
            </div>
        @endif
        
        <h2 class="text-center" style="padding: 1rem 0 1rem 0;">@lang('common.temp_user_user_entites') :</h2>

        @if (count($tempUsers) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.name')</td>
                        <td>@lang('common.created_at')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tempUsers as $tempUser)
                        <tr>
                            <td>{{$tempUser->name}} {{$tempUser->surname}}</td>
                            <td>{{$tempUser->created_at}}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ URL::to('/admin/temp-user/user/show/' . $tempUser->id) }}">
                                    @lang('common.show')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_temp_users_description')</h3>
            </div>
        @endif
    </div>
</div>
@endsection