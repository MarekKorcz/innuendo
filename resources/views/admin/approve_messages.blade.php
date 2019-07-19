@extends('layouts.app')
@section('content')
<div class="container">

    <h1 class="text-center">All approve messages</h1>
    
    <table class="table table-striped table-bordered">
        <thead>
            <tr>                
                <td>Owner</td>
                <td>Approved</td>
                <td>Created At</td>
                <td>Promo</td>
                <td>Action</td>
            </tr>
        </thead>
        <tbody>
            @foreach($promoCodes as $promoCode)
                <tr>
                    <td>{{$promoCode->boss->name}} {{$promoCode->boss->surname}}</td>
                    <td>
                        @if ($promoCode->boss->isApproved == 0)
                            No
                        @else
                            Yes
                        @endif
                    </td>
                    <td>{{$promoCode->activation_date}}</td>
                    <td>{{$promoCode->promo->title}}</td>
                    <td>
                        <a class="btn btn-success" href="{{ URL::to('/admin/approve/messages/' . $promoCode->boss->id) }}">
                            Show
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection