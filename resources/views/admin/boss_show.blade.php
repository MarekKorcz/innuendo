@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            
            <div class="text-center">
                <h2>@lang('common.bosses'):</h2>
            </div>

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.name')</td>
                        <td>@lang('common.email_address')</td>
                        <td>@lang('common.phone_number')</td>
                        <td>@lang('common.created_at')</td>
                        @if ($boss->promoCode !== null)
                            <td>@lang('common.is_approved')</td>
                            <td>@lang('common.approved_messages')</td>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$boss->name}} {{$boss->surname}}</td>
                        <td>{{$boss->email}}</td>
                        <td>{{$boss->phone_number}}</td>
                        <td>{{$boss->created_at}}</td>
                        @if ($boss->promoCode !== null)
                            <td>
                                @if ($boss->isApproved == 0)
                                    @lang('common.no')
                                @else
                                    @lang('common.yes')
                                @endif
                            </td>
                            <td>
                                <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/admin/approve/messages/' . $boss->id) }}">
                                    @lang('common.show')
                                </a>
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>

            <div class="text-center">
                <h2>@lang('common.properties_owned_by_bosses'):</h2>
            </div>

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
                    @foreach($properties as $property)
                        <tr>
                            <td>{{$property->name}}</td>
                            <td>{{$property->street}}</td>
                            <td>{{$property->created_at}}</td>
                            <td>
                                <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/property/' . $property->id) }}">
                                    @lang('common.show')
                                </a>
                                <a class="btn pallet-2-4" style="color: white;" href="{{ URL::to('/property/' . $property->id . '/edit') }}">
                                    @lang('common.edit')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-1"></div>
    </div>
</div>
@endsection