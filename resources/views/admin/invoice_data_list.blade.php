@extends('layouts.app')
@section('content')
<div class="container" style="padding: 18px;">
    
    <div style="padding: 1rem 0 1rem 0">
        
        <div class="row">
            <div class="col-1"></div>
            <div class="col-10">
                <h2 class="text-center">@lang('common.invoice_datas')</h2>

                @if (count($invoiceDatas) > 0)
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>                
                                <td>@lang('common.name')</td>
                                <td>@lang('common.email')</td>
                                <td>NIP</td>
                                <td>@lang('common.created_at')</td>
                                <td>@lang('common.canceled')</td>
                                <td>@lang('common.action')</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoiceDatas as $invoice)
                                <tr>
                                    <td>{{$invoice->company_name}}</td>
                                    <td>{{$invoice->email}}</td>
                                    <td>{{$invoice->nip}}</td>
                                    <td>{{$invoice->created_at}}</td>
                                    <td>
                                        @if ($invoice->deleted_at == null)
                                            @lang('common.no')
                                        @else
                                            {{$invoice->deleted_at}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($invoice->deleted_at == null)
                                            {{ Form::open(['action' => ['AdminController@invoiceDataSoftDelete', $invoice->id]]) }}
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="submit" value="@lang('common.deactivate')" class="btn pallet-2-4" style="color: white;">
                                            {{ Form::close() }}
                                        @else
                                            <div style="float: left; padding: 0 1px 0 1px;">
                                                <a class="btn pallet-2-4" style="color: white;" href="{{ URL::to('/admin/invoice-data/undelete/' . $invoice->id) }}">
                                                    @lang('common.activate')
                                                </a>
                                            </div>
                                            <div style="float: left; padding: 0 1px 0 1px;">
                                                {{ Form::open(['action' => ['AdminController@invoiceDataHardDelete', $invoice->id]]) }}
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="submit" value="@lang('common.delete')" class="btn pallet-2-3" style="color: white;">
                                                {{ Form::close() }}
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center" style="padding: 1rem;">
                        <h4>@lang('common.no_invoice_datas')</h4>
                    </div>
                @endif
                
                <div class="text-center">
                    <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('/admin/invoice-data/create') }}">
                        @lang('common.create')
                    </a>
                </div>
            </div>
            <div class="col-1"></div>
        </div>
    </div>
</div>
@endsection