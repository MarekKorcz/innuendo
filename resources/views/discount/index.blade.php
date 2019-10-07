@extends('layouts.app')
@section('content')

{!! Html::style('css/discount_index.css') !!}
{!! Html::script('js/discount_index.js') !!}

<div class="container">
    <div style="padding: 2rem 0 2rem 0;">
        
        <div class="row">
            <div class="col-4"></div>
            <div class="col-4"></div>
            <div class="col-4 text-center">
                <a class="btn btn-success" href="{{ URL::to('/admin/discount/create') }}">
                    @lang('common.discount_create')
                </a>
            </div>
        </div>
        
        <h1 class="text-center" style="padding-bottom: 1rem;">@lang('common.discounts')</h1>

        @if (count($discounts) > 0)
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>                
                        <td>@lang('common.label')</td>
                        <td>@lang('common.description')</td>
                        <td>@lang('common.worker_threshold')</td>
                        <td>@lang('common.percent')</td>
                        <td>@lang('common.action')</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discounts as $discount)
                        <tr>
                            <td>
                                @if (Session('locale') == "en")
                                    {{$discount->name_en}}
                                @else
                                    {{$discount->name}}
                                @endif
                            </td>
                            <td>
                                @if (Session('locale') == "en")
                                    {{$discount->description_en}}
                                @else
                                    {{$discount->description}}
                                @endif
                            </td>
                            <td>{{$discount->worker_threshold}}</td>
                            <td>{{$discount->percent}}</td>
                            <td>
                                <div class="text-center">
                                    <a class="btn btn-danger delete" style="color: white;" data-discount_id="{{$discount->id}}">
                                        @lang('common.delete')
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center">
                <h3>@lang('common.no_discounts_info')</h3>
                <a class="btn btn-success" href="{{ URL::to('/admin/discount/create') }}">
                    @lang('common.create')
                </a>
            </div>
        @endif
    </div>
    
    <div id="deleteDiscount" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.discount_delete')</h4>
                <button id="deleteDiscountCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn btn-danger">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
</div>
@endsection