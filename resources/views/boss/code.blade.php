@extends('layouts.app')
@section('content')

{!! Html::style('css/code.css') !!}
{!! Html::script('js/code.js') !!}

<div class="container">
    
    <div style="padding: 1rem 0 1rem 0">        
        @if ($code !== null)
            <div class="text-center" style="padding-top: 1rem;">
                <h2>@lang('common.register_code')</h2>
            </div>

            <div class="row" style="padding: 12px;">
                <div class="col-12">
                    <div class="card">
                        <div class="text-right" style="padding: 6px;">
                            <a class="btn pallet-2-2 delete" style="color: white;" data-code_id="{{$code->id}}">
                                @lang('common.delete')
                            </a>
                        </div>
                        <div class="text-center">
                            {{ Form::open(['id' => 'register-code', 'action' => 'BossController@setCode', 'method' => 'POST']) }}
                                <div style="padding: 0 12px 12px 12px;">
                                    <input name="code_id" type="hidden" value="{{$code->id}}">

                                    @if ($code->code !== null)
                                        <div class="text-center" style="padding-bottom: 2rem;">
                                            <h3 style="padding-bottom: 1rem;">
                                                @lang('common.registration_code_description')
                                            </h3>
                                            <p>
                                                @lang('common.registration_code'):
                                                <input class="code-text" name="code-text" type="text" value="{{$code->code}}" style="margin: 0px 12px 0px 12px;">
                                                <a class="btn pallet-1-3 copy-button" style="color: white;">
                                                    @lang('common.registration_code_copy')
                                                </a>
                                            </p>

                                            <input name="code" type="hidden" value="false">
                                            <input type="submit" value="@lang('common.turn_registration_off')" class="btn pallet-2-4" style="color: white;">
                                        </div>
                                    @else
                                        <div class="text-center" style="padding-bottom: 2rem;">
                                            <h3 style="padding-bottom: 1rem;">
                                                @lang('common.turn_registration_on_description')
                                            </h3>
                                            <input name="code" type="hidden" value="true">
                                            <input type="submit" value="@lang('common.turn_registration_on')" class="btn pallet-1-3" style="color: white;">
                                        </div>
                                    @endif
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center" style="padding: 1rem;">
                <h2 style="padding-bottom: 1rem;">@lang('common.register_code')</h2>
                <h4>@lang('common.register_code_description')</h4>
                <div style="padding: 1rem;">
                    <a class="btn pallet-2-1" style="color: white;" href="{{ action('BossController@addCode') }}">
                        @lang('common.add_new_code')
                    </a>
                </div>
            </div>
        @endif
    </div>
    
    <div id="deleteCode" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.code_delete')</h4>
                <button id="deleteCodeCloseButton" class="close" data-dismiss="modal">×</button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <form method="POST" accept-charset="UTF-8">
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="submit" value="@lang('common.delete')" class="btn pallet-2-2" style="color: white;">
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div id="background"></div>
    
</div>

@endsection