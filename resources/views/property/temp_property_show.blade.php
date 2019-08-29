@extends('layouts.app')
@section('content')
<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                {!!Form::open(['action' => ['PropertyController@tempPropertyDestroy', $tempProperty->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
                    {{ Form::hidden('_method', 'DELETE') }}
                    <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
                {!!Form::close()!!}
            </div>
            <ul class="nav navbar-nav">
                <li>
                    <a class="btn btn-success" href="{{ URL::to('temp-property/' . $tempProperty->id . '/edit') }}">
                        @lang('common.edit')
                    </a>
                </li>
            </ul>
        </nav>

        <h2 class="text-center">@lang('common.temporary_property')</h2>
    
        <table class="table table-striped">
            <tr>
                <th>@lang('common.name') :</th>
                <th>@lang('common.street') :</th>
                <th>@lang('common.street_number') :</th>
                <th>@lang('common.house_number') :</th>
                <th>@lang('common.city') :</th>
                <th>@lang('common.owner') :</th>
            </tr>
            <tr>
                <td>{{ $tempProperty->name }}</td>
                <td>{{ $tempProperty->street }}</td>
                <td>{{ $tempProperty->street_number }}</td>
                <td>{{ $tempProperty->house_number }}</td>
                <td>{{ $tempProperty->city }}</td>
                @if ($tempProperty->temp_user_id > 0)
                    <td>{{ $tempProperty->owner->name }} {{ $tempProperty->owner->surname }}</td>
                @else
                    <td>@lang('common.none')</td>
                @endif
            </tr>
        </table>
        
        <h3 class="text-center">@lang('common.subscriptions')</h3>

        @if (count($subscriptions) > 0)
            <table class="table table-striped">
                <tr>
                    <th>@lang('common.name') :</th>
                    <th>@lang('common.description') :</th>
                    <th>@lang('common.old_price') :</th>
                    <th>@lang('common.new_price') :</th>
                    <th>@lang('common.quantity') :</th>
                    <th>@lang('common.duration') :</th>
                </tr>
                    @foreach ($subscriptions as $subscription)
                        <tr>
                            <td>{{ $subscription->name }}</td>
                            <td>{!! $subscription->description !!}</td>
                            <td>{{ $subscription->old_price }}</td>
                            <td>{{ $subscription->new_price }}</td>
                            <td>{{ $subscription->quantity }}</td>
                            <td>{{ $subscription->duration }}</td>
                        </tr>
                    @endforeach
            </table>
        @else
            <p class="text-center">@lang('common.there_is_no_subscriptions_attached')</p>
        @endif
    </div>
</div>
@endsection