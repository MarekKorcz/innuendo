@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Moje konto</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="container">
                        Jesteś zalogowany jako - <strong>{{$user->name}}</strong>
                        
                        
                        </br>
                            Links
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection