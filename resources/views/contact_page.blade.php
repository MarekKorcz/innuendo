@extends('layouts.app')
@section('content')

{!! Html::script('js/contact_page.js') !!}

<div class="container">
    <div class="row" style="padding: 2rem 0 2rem 0;">
        <div class="col-1 col-sm-1 col-md-2 col-lg-3 col-xl-3"></div>
        <div class="col-10 col-sm-10 col-md-8 col-lg-6 col-xl-6">
            
            <div class="text-center">
                <h1>@lang('contact.contact_us')</h1>
            </div>
            
            {{ Form::open(['id' => 'contact-page', 'action' => 'HomeController@contactPageUpdate', 'method' => 'POST']) }}

                <div class="form-group">
                    <label for="email">@lang('contact.email'):</label>
                    {{ Form::text('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control')) }}
                    <div id="email-error" style="padding-left: 6px;"></div>
                </div>
                <div class="form-group">
                    <label for="topic">@lang('contact.topic'):</label>
                    {{ Form::text('topic', Input::old('topic'), array('id' => 'topic', 'class' => 'form-control')) }}
                    <div id="topic-error" style="padding-left: 6px;"></div>
                </div>
                <div class="form-group">
                    <label for="message">@lang('contact.message'):</label>
                    {{ Form::textarea('message', Input::old('message'), array('id' => 'message', 'class' => 'form-control')) }}
                    <div id="message-error" style="padding-left: 6px;"></div>
                </div>               

                <div class="text-center">
                    <input type="submit" value="@lang('contact.send')" class="btn pallet-1-3" style="color: white;">
                </div>

            {{ Form::close() }}
            
        </div>
        <div class="col-1 col-sm-1 col-md-2 col-lg-3 col-xl-3"></div>
    </div>
    
    @if ($showBanner)
        @include('layouts.banner')
    @endif
</div>
@endsection