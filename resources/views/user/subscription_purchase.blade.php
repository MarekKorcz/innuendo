@extends('layouts.app')

@section('content')

<!--{!! Html::style('css/bootstrap_datepicker.css') !!}
{!! Html::script('js/bootstrap_datepicker.js') !!}-->
{!! Html::script('js/subscription_purchase.js') !!}

<div class="container">

    <h1 class="text-center" style="padding: 2rem;">{{ $subscription->name }}</h1>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <h3>@lang('common.description')</h3>
                <p>@lang('common.label') : <strong>{{$subscription->name}}</strong></p>
                <p>@lang('common.description') : <strong>{{ $subscription->description }}</strong></p>
                <p>@lang('common.price') :  
                    <strike>{{$subscription->old_price}}</strike>
                    <strong>{{$subscription->new_price}}</strong>
                </p>
                <p>@lang('common.number_of_massages_to_use_per_month'): <strong>{{$subscription->quantity}}</strong></p>
                <p>@lang('common.subscription_duration') : <strong>{{$subscription->duration}} @lang('common.months_count')</strong></p>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <img class="img-fluid" src="/img/column2.jpg">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-lg-12 col-md-12">
                <div class="text-center" style="padding-top: 27px;">
                    <div style="background-color: white; border: 2px darkgrey solid;" class="jumbotron">
                        <h2 style="padding-bottom: 21px;">@lang('common.regulations')</h2>
                        <div style="text-align: left;">
                            <p><strong>1.</strong> TheBalance.com and its affiliated sites (collectively, the “Site”) are Dotdash brands, owned and operated by About, Inc. and its affiliates ("The Balance", the "Company", "we", or "us"). Access to and use of the Site is subject to these terms and conditions of use (“Terms of Use”).</p>

                            <p><strong>2.</strong> "Site" or "The Balance" shall include any information or services made available by The Balance, regardless of the medium, and shall include, without limitation any affiliated websites, mobile applications, videos, products and applications we make available. We reserve the right at any time, and from time to time, to modify, suspend or discontinue (temporarily or permanently) the Site, or any part of the Site, with or without notice.
                            The Site is not intended for users under 13 years of age. If you are under 13, do not use the Site and do not provide us with any personal information.
                            We make no claims that the Site or any of its content is accessible or appropriate outside of the United States.

                            <p><strong>3.</strong> Our Right to Modify These Terms of Use
                            We reserve the right to change these Terms of Use at any time. You should check this page regularly. The changes will appear on the Site and will be effective when we post the changes. Your continued use of the Site means you agree to and accept the changes.</p>

                            <p><strong>4.</strong> Our Privacy Policy
                            Our Privacy Policy contains further information about how data is collected, used and made available on or by our Site. We encourage you to read it, here.</p>

                            <p><strong>5.</strong> The Balance Intellectual Property
                            Your Limited License to our Intellectual Property</p>

                            <p><strong>6.</strong> The materials used and displayed on the Site, including but not limited to text, software, photographs, graphics, illustrations and artwork, video, music and sound, and names, logos, trademarks and service marks, are the property of The Balance, About, Inc., or its affiliates or licensors and are protected by copyright, trademark and other laws. Any such content may be used solely for your personal, non-commercial use. You agree not to modify, reproduce, retransmit, distribute, disseminate, sell, publish, broadcast or circulate any such material without the prior written permission of The Balance. The Balance grants you a personal, non-exclusive, non-transferable, revocable license to use the Site and any materials on the site for non-commercial purposes subject to these Terms of Use.</p>

                            <p><strong>7.</strong> The Balance Trademarks and Logos</p>

                            <p><strong>8.</strong> The terms The Balance, www.thebalance.com and other The Balance trademarks and services marks, and associated logos and all related names, logos, product and service names, designs and slogans are trademarks of The Balance or its affiliates or licensors. You may not use such marks without the prior written permission of The Balance. All other names, logos, product and service names, designs and slogans on the Site are the trademarks of their respective owners.</p>

                            <p><strong>9.</strong> Reliance on Information on Site
                            We have no obligation to, and you should not expect us to, review content on our Site, including User Contributions (defined below) or contributions by our independent contributors.</p>
                        </div>
                        <div class="text-center" style="padding-top: 21px;">
                            {{ Form::open(['id' => 'purchaseForm', 'action' => 'UserController@subscriptionPurchased', 'method' => 'POST']) }}

<!--                                <div class="dates">
                                    <label>Wybierz datę rozpoczęcia subskrypcji:</label>
                                    <input id="subscription_start" 
                                           name="subscription_start"
                                           type="text" 
                                           value="{{date('Y-m-d')}}"
                                           placeholder="{{date('Y-m-d')}}" 
                                           class="form-control" 
                                           autocomplete="off"
                                    >
                                </div>-->
                            
                                <div class="form-group">
                                    
                                    
<!--                                    <label>
                                        <span style="font-size: 21px;">@lang('common.terms_accept') :</span>
                                    </label>-->
                                    
                                    
                                    <!--todo: jak tu zrobić tłumaczenie?????-->
                                    {!! Html::decode(Form::label('terms','<span style="font-size: 21px;">Akceptuje powyższy regulamin:</span>')) !!}
                                    
                                    
                                    
                                    {{ Form::checkbox('terms', null, null, array('class' => 'form-control')) }}
                                </div>
                                
                                {{ Form::hidden('property_id', $property->id) }}
                                {{ Form::hidden('subscription_id', $subscription->id) }}
                                
                                <label>@lang('common.subscription_first_time_activation_info')</label>
                                
                                <input type="submit" value="@lang('common.order')" class="btn btn-primary btn-lg">

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection