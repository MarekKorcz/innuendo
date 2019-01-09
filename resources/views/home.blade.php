@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in as: 
                    <?php 
                    if (auth()->user()->isAdmin == 1)
                    { 
                    ?>
                        <div class="panel-body">
                            <a href="{{url('admin/routes')}}">Admin</a>
                        </div>
                    <?php
                    } 
                    else 
                    {
                    ?>
                        <div class="panel-body">
                            Normal User
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection