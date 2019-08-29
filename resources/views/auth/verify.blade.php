@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">@lang('common.verify_your_email_address')</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            @lang('common.verify_your_email_address_description')
                        </div>
                    @endif

                    @lang('common.verify_your_email_address_description_2')
                    @lang('common.verify_your_email_address_description_3') , <a href="{{ route('verification.resend') }}">@lang('common.verify_your_email_address_description_button')</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
