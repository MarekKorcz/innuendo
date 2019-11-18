@extends('layouts.app')
@section('content')

{!! Html::style('css/home.css') !!}

<div class="container" style="padding: 2rem;">
    <div class="row">
        <div class="col-1"></div>
        <div class="col-10">
            <div class="card-header text-center">
                <span style="font-size: 27px;">
                    @lang('navbar.my_account') 
                </span> 
                - @lang('common.logged_in_as') 
                <strong>
                    {{$user->name}} {{$user->surname}}
                </strong>
            </div>
            <div class="wrapper">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.create_boss_with_first_property')</h4>
                        <p class="card-text text-center">
                            @lang('common.create_boss_with_first_property_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/admin/boss/create') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.contact_messages')</h4>
                        <p class="card-text text-center">
                            @lang('common.contact_messages_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('admin/contact/messages') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.approval_messages')</h4>
                        <p class="card-text text-center">
                            @lang('common.approved_messages_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('admin/approve/messages') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.all_graphic_requests')</h4>
                        <p class="card-text text-center">
                            @lang('common.all_graphic_requests_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('admin/graphic-requests') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.bosses_list')</h4>
                        <p class="card-text text-center">
                            @lang('common.bosses_list_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/admin/boss/list') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.employees_list')</h4>
                        <p class="card-text text-center">
                            @lang('common.employees_list_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/admin/employee/list') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.users_list')</h4>
                        <p class="card-text text-center">
                            @lang('common.users_list_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/admin/user/list') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.all_properties')</h4>
                        <p class="card-text text-center">
                            @lang('common.all_properties_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/property/index') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.subscriptions')</h4>
                        <p class="card-text text-center">
                            @lang('common.subscriptions_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/subscription/index') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.categories_and_items')</h4>
                        <p class="card-text text-center">
                            @lang('common.categories_and_items_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/category/index') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.promos_list')</h4>
                        <p class="card-text text-center">
                            @lang('common.promos_list_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('/admin/promo/list') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.discounts')</h4>
                        <p class="card-text text-center">
                            @lang('common.discounts_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('admin/discount/index') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">@lang('common.invoice_datas')</h4>
                        <p class="card-text text-center">
                            @lang('common.invoice_datas_description')
                        </p>
                        <div class="text-center">
                            <a class="btn pallet-2-3" style="color: white;" href="{{ URL::to('admin/invoice-data/list') }}">
                                @lang('common.show')
                            </a>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
        <div class="col-1"></div>
    </div>
    
    @if ($showBanner)
        @include('layouts.banner')
    @endif
</div>
@endsection