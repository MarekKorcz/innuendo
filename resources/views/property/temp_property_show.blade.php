@extends('layouts.app')
@section('content')

{!! Html::style('css/temp_property_show.css') !!}
{!! Html::script('js/temp_property_show.js') !!}

<div class="container">
    <div class="jumbotron" style="margin-top: 15px;">
        <nav class="navbar navbar-inverse">
            <div class="navbar-header">
                <div class="text-right" style="padding: 6px;">
                    <a class="btn pallet-2-2 delete" style="color: white;" data-temp_property_id="{{$tempProperty->id}}">@lang('common.delete')</a>
                </div>
            </div>
            <ul class="nav navbar-nav">
                <li>
                    <a class="btn pallet-1-3" style="color: white;" href="{{ URL::to('temp-property/' . $tempProperty->id . '/edit') }}">
                        @lang('common.edit')
                    </a>
                </li>
            </ul>
        </nav>

        <h2 class="text-center">@lang('common.temporary_property')</h2>
    
        <table class="table table-striped">
            <tr>
                <th>@lang('common.label'):</th>
                <th>@lang('common.street'):</th>
                <th>@lang('common.street_number'):</th>
                <th>@lang('common.house_number'):</th>
                <th>@lang('common.city'):</th>
                <th>@lang('common.owner'):</th>
            </tr>
            <tr>
                <td>{{ $tempProperty->name }}</td>
                <td>{{ $tempProperty->street }}</td>
                <td>{{ $tempProperty->street_number }}</td>
                <td>{{ $tempProperty->house_number }}</td>
                <td>{{ $tempProperty->city }}</td>
                @if ($tempProperty->tempUser->id !== null)
                    <td>
                        <a href="{{ URL::to('admin/temp-user/boss/show/' . $tempProperty->tempUser->id) }}">
                            {{ $tempProperty->tempUser->name }} {{ $tempProperty->tempUser->surname }}
                        </a>
                    </td>
                @else
                    <td>@lang('common.none')</td>
                @endif
            </tr>
        </table>
    </div>
    
    <div id="deleteTempProperty" class="modal hide">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="text-center">@lang('common.temp_property_delete')</h4>
                <button id="deleteTempPropertyCloseButton" class="close" data-dismiss="modal">×</button>
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