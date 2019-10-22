@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <h2 class="text-center">@lang('private_policy.private_policy')</h2>
            </div>
        </div>
    </div>
        
    <div class="container">
        <div class="row" style="padding: 1rem 0 1rem 0;">
            <div class="col-1"></div>
            <div id="first-paragraph" class="col-10">
                <p>
                    @lang('private_policy.header_info') 
                    <strong>
                        {{ config('app.name') }} {{ config('app.name_2nd_part') }}
                    </strong>
                </p>
                <p>
                    1. @lang('private_policy.first_paragraph_1')</br>
                    @lang('private_policy.first_paragraph_1_1')
                    <strong>
                        {{ config('app.name') }} {{ config('app.name_2nd_part') }}.
                    </strong>
                </p>
                <p>2. @lang('private_policy.first_paragraph_2')</p>
                <p>3. @lang('private_policy.first_paragraph_3')</p>
                <p>4. @lang('private_policy.first_paragraph_4')</p>
                <p>
                    5. @lang('private_policy.first_paragraph_5')
                    <ul style="list-style: none;">
                        <li>a) @lang('private_policy.first_paragraph_5_1')</li>
                        <li>b) @lang('private_policy.first_paragraph_5_2')</li>
                    </ul>
                </p>
                <p>6. @lang('private_policy.first_paragraph_6')</p>
                <p>7. @lang('private_policy.first_paragraph_7')</p>
                <p>8. @lang('private_policy.first_paragraph_8')</p>
                <p>9. @lang('private_policy.first_paragraph_9')</p>
                <p>10. @lang('private_policy.first_paragraph_10')</p>
                <p>
                    11. @lang('private_policy.first_paragraph_11')
                    <strong>
                        {{ config('app.name') }} {{ config('app.name_2nd_part') }}.
                    </strong>
                    @lang('private_policy.first_paragraph_11_1')</br>
                    @lang('private_policy.first_paragraph_11_2')
                </p>
            </div>
            <div class="col-1"></div>
        </div>
    </div>

@endsection