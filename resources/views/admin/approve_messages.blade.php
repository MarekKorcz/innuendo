@extends('layouts.app')
@section('content')
<div class="container">

    <h1 class="text-center">@lang('common.approval_messages')</h1>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>@lang('common.owner')</td>
                <td>@lang('common.approved')</td>
                <td>@lang('common.created_at')</td>
                <td>Promo</td>
                <td>@lang('common.action')</td>
            </tr>
        </thead>
        <tbody>
            @foreach($promoCodes as $promoCode)
                <tr>
                    <td>{{$promoCode->boss->name}} {{$promoCode->boss->surname}}</td>
                    <td>
                        @if ($promoCode->boss->isApproved == 0)
                            @lang('common.no')
                        @else
                            @lang('common.yes')
                        @endif
                    </td>
                    <td>{{$promoCode->activation_date}}</td>
                    <td>{{$promoCode->promo->title}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ URL::to('/admin/approve/messages/' . $promoCode->boss->id) }}">
                            @lang('common.show')
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection