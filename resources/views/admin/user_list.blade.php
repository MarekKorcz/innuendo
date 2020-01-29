@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <div style="padding: 1rem 0 1rem 0">
        
        <h2 class="text-center" style="padding-bottom: 1rem;">@lang('common.users'):</h2>

        @if (count($users) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.name')</td>
                        <td>@lang('common.boss')</td>
                        <td>@lang('common.created_at')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->name}} {{$user->surname}}</td>
                            <td>
                                <a href="{{ URL::to('/admin/boss/show/' . $user->boss->id) }}">
                                    {{$user->boss->name}} {{$user->boss->surname}}
                                </a>
                            </td>
                            <td>{{$user->created_at}}</td>
                            <td>
                                <a class="btn pallet-2-4" style="color: white;" href="{{ URL::to('/admin/user/show/' . $user->id) }}">
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
    </div>
</div>
@endsection