@extends('layouts.app')
@section('content')
<div class="container">

    <div class="text-center" style="padding: 2rem;">
        <h1>
            @lang('common.users')
        </h1>
    </div>
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            @if (count($users) > 0)
                <div id="table" style="padding: 0 2rem 2rem 2rem;">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>@lang('common.name')</td>
                                <td>@lang('common.surname')</td>
                                <td>@lang('common.property')</td>
                                <td>@lang('common.action')</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->surname}}</td>
                                    <td>{{$user->property->name}}</td>
                                    <td class="text-center">
                                        <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/employee/backend-appointment/index/' . $user->id) }}">
                                            @lang('common.show')
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center" style="padding: 0 0 2rem 0;">
                    <h3>
                        @lang('common.no_users_currently_available')
                    </h3>
                </div>
            @endif
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection