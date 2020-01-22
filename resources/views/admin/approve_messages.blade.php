@extends('layouts.app')
@section('content')
<div class="container">

    <div style="padding: 2rem 0 2rem 0;">
        
        <h1 class="text-center" style="padding-bottom: 1rem;">@lang('common.approval_messages')</h1>
        
        @if (count($promoCodes) > 0)
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
                                @if ($promoCode->boss->is_approved == 0)
                                    @lang('common.no')
                                @else
                                    @lang('common.yes')
                                @endif
                            </td>
                            <td>{{$promoCode->activation_date}}</td>
                            <td>{{$promoCode->promo->title}}</td>
                            <td>
                                <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/admin/approve/messages/' . $promoCode->boss->id . '/' . $promoCode->promo->id) }}">
                                    @lang('common.show')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_approve_messages')</h3>
            </div>
        @endif
    </div>
    
</div>
@endsection