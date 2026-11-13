@extends('layouts.app')
@section('content')

<script src="{{ asset('js/contact_page.js') }}" defer></script>

<div class="container">
    <div class="row" style="padding: 2rem 0 2rem 0;">
        <div class="col-1 col-sm-1 col-md-2 col-lg-3 col-xl-3"></div>
        <div class="col-10 col-sm-10 col-md-8 col-lg-6 col-xl-6">

            <div class="text-center">
                <h1>@lang('contact.contact_us')</h1>
            </div>

            <form id="contact-page" action="{{ action([\App\Http\Controllers\HomeController::class, 'contactPageUpdate']) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">@lang('contact.email'):</label>
                    <input type="text" name="email" id="email" value="{{ old('email') }}" class="form-control">
                    <div id="email-error" style="padding-left: 6px;"></div>
                </div>
                <div class="form-group">
                    <label for="topic">@lang('contact.topic'):</label>
                    <input type="text" name="topic" id="topic" value="{{ old('topic') }}" class="form-control">
                    <div id="topic-error" style="padding-left: 6px;"></div>
                </div>
                <div class="form-group">
                    <label for="message">@lang('contact.message'):</label>
                    <textarea name="message" id="message" class="form-control">{{ old('message') }}</textarea>
                    <div id="message-error" style="padding-left: 6px;"></div>
                </div>

                <div class="text-center">
                    <input type="submit" value="@lang('contact.send')" class="btn pallet-1-3" style="color: white;">
                </div>
            </form>

        </div>
        <div class="col-1 col-sm-1 col-md-2 col-lg-3 col-xl-3"></div>
    </div>

    @if ($showBanner)
        @include('layouts.banner')
    @endif
</div>
@endsection
