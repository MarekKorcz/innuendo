@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Moje konto</div>

                <div class="card-body">
                    <div class="container">
                        <p>Jesteś zalogowany jako - <strong>{{$user->name}}</strong></p>
                        
                        @if (auth()->user()->isEmployee)
                            <a class="btn btn-success" href="{{ URL::to('/employee/backend-graphic') }}">
                                Grafiki
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection