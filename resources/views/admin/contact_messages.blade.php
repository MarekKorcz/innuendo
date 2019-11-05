@extends('layouts.app')
@section('content')
<div class="container">

    <div style="padding: 2rem 0 2rem 0;">
        
        <h1 class="text-center" style="padding-bottom: 1rem;">@lang('common.contact_messages')</h1>
        
        @if (count($contactMessages) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.topic')</td>
                        <td>@lang('common.email_address')</td>
                        <td>@lang('common.text')</td>
                        <td>@lang('common.created_at')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contactMessages as $contactMessage)
                        <tr>
                            <td>{{$contactMessage->topic}}</td>
                            <td>{{$contactMessage->email}}</td>
                            <td>{{$contactMessage->text}}</td>
                            <td>{{$contactMessage->created_at}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_contact_messages')</h3>
            </div>
        @endif
    </div>
    
</div>
@endsection