@extends('layouts.app')
@section('content')
<div class="container">
    
    <div style="padding: 2rem 0 2rem 0">
        <h1 class="text-center" style="padding-bottom: 1rem;">@lang('common.subscriptions')</h1>

        @if (count($subscriptions) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.name')</td>
                        <td>@lang('common.description')</td>
                        <td>@lang('common.old_price')</td>
                        <td>@lang('common.new_price')</td>
                        <td>@lang('common.quantity')</td>
                        <td>@lang('common.duration')</td>
                        <td>@lang('common.worker_quantity')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscriptions as $subscription)
                        <tr>
                            <td>{!! $subscription->name !!}</td>
                            <td>{!! $subscription->description !!}</td>
                            <td>{{$subscription->old_price}}</td>
                            <td>{{$subscription->new_price}}</td>
                            <td>{{$subscription->quantity}}</td>
                            <td>{{$subscription->duration}}</td>
                            <td>
                                @if ($subscription->worker_quantity == 0)
                                    @lang('common.infinity')
                                @elseif ($subscription->worker_quantity !== null)
                                    {{ $subscription->worker_quantity }}
                                @endif
                            </td>
                            <td>
                                <div style="padding: 1px;">
                                    <a class="btn btn-primary" href="{{ URL::to('/subscription/show/' . $subscription->id) }}">
                                        @lang('common.show')
                                    </a>
                                </div>
                                <div style="padding: 1px;">
                                    <a class="btn btn-success" href="{{ URL::to('/subscription/' . $subscription->id . '/edit') }}">
                                        @lang('common.edit')
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_subscriptions_description')</h3>
            </div>
        @endif
    </div>
</div>
@endsection