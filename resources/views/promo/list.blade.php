@extends('layouts.app')
@section('content')
<div class="container">
    
    <div class="text-center" style="padding-top: 1rem;">
        <h1>@lang('common.promos_list')</h1>
    </div>

    @if (count($promos) > 0)
    
        <div class="text-right" style="padding: 1rem;">
            <a class="btn btn-success" href="{{ URL::to('/admin/promo/create') }}">
                @lang('common.promo_create_new')
            </a>
        </div>
    
        <div style="padding-bottom: 1rem;">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.title')</td>
                        <td>@lang('common.description')</td>
                        <td>@lang('common.code_count')</td>
                        <td>@lang('common.is_active')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($promos as $promo)
                        <tr>
                            <td>
                                @if (Session('locale') == "en")
                                    {{ $promo->title_en }}
                                @else
                                    {{ $promo->title }}
                                @endif
                            </td>
                            <td>
                                @if (Session('locale') == "en")
                                    {{ $promo->description_en }}
                                @else
                                    {{ $promo->description }}
                                @endif
                            </td>
                            <td>{{ $promo->total_code_count}}</td>
                            <td>
                                @if ($promo->isActive == 0)
                                    @lang('common.no')
                                @else
                                    @lang('common.yes')
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-success" style="margin: 3px;" href="{{ URL::to('/admin/promo/show/' . $promo->id) }}">
                                    @lang('common.show')
                                </a>
                                <a class="btn btn-primary" style="margin: 3px;" href="{{ URL::to('/admin/promo/edit/' . $promo->id) }}">
                                    @lang('common.edit')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center">
            <h3>@lang('common.promo_create_description')</h3>
            <h4>@lang('common.promo_create_description_2')</h4>
            <div style="padding: 1rem;">
                <a class="btn btn-success btn-lg" href="{{ URL::to('/admin/promo/create') }}">
                    @lang('common.show')
                </a>
            </div>
        </div>
    @endif
</div>
@endsection