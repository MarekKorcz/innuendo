@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <h2 class="text-center">@lang('cookies.cookies_policy')</h2>
            </div>
        </div>
    </div>
        
    <div class="container">
    
        <div class="row" style="padding: 1rem 0 1rem 0;">
            <div class="col-1"></div>
            <div id="first-paragraph" class="col-10">
                <p>1. @lang('cookies.first_paragraph_1')</p>
                <p>2. @lang('cookies.first_paragraph_2')</p>
                <p>
                    3. @lang('cookies.first_paragraph_3_1') 
                    <strong>
                        {{ config('app.name') }} {{ config('app.name_2nd_part') }} 
                    </strong>
                    @lang('cookies.first_paragraph_3_2')
                    Domaniewska 17/19, 02-672 Warszawa
                </p>
                <p>4. @lang('cookies.first_paragraph_4')</p>
                    <ul style="list-style: none;">
                        <li>a) @lang('cookies.first_paragraph_4_a')</li>
                        <li>b) @lang('cookies.first_paragraph_4_b')</li>
                        <li>c) @lang('cookies.first_paragraph_4_c')</li>
                    </ul>
                <p>5. @lang('cookies.first_paragraph_5')</p>
                <p>6. @lang('cookies.first_paragraph_6')</p>
                    <ul style="list-style: none;">
                        <li>a) @lang('cookies.first_paragraph_6_a')</li>
                        <li>b) @lang('cookies.first_paragraph_6_b')</li>
                        <li>c) @lang('cookies.first_paragraph_6_c')</li>
                        <li>d) @lang('cookies.first_paragraph_6_d')</li>
                        <li>e) @lang('cookies.first_paragraph_6_e')</li>
                    </ul>
                <p>7. @lang('cookies.first_paragraph_7')</p>
                <p>8. @lang('cookies.first_paragraph_8')</p>
                <p>9. @lang('cookies.first_paragraph_9')</p>
                <p>
                    10. @lang('cookies.first_paragraph_10_1')
                    Domaniewska 17/19, 02-672 Warszawa
                    @lang('cookies.first_paragraph_10_2')
                </p>
            </div>
            <div class="col-1"></div>
        </div>
        
        <div id="terms" class="text-center" style="padding: 1rem 0 2rem 0;">
            @lang('cookies.terms_info')
            <a href="https://wszystkoociasteczkach.pl/" target="_blanc">https://wszystkoociasteczkach.pl/</a>
        </div>
    </div>

@endsection