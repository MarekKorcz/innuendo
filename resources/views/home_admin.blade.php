@extends('layouts.app')
@section('content')

{!! Html::style('css/home.css') !!}

<div class="container" style="padding: 2rem;">
    <div class="card-header text-center">
        <span style="font-size: 27px;">Moje konto</span> - zalogowany jako <strong>{{$user->name}} {{$user->surname}}</strong>
    </div>
    <div class="wrapper">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Create boss with first property</h4>
                <p class="card-text text-center">
                    Create initial boss account with property
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/admin/boss/create') }}">
                        Create
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Bosses list</h4>
                <p class="card-text text-center">
                    View with list of active bosses and TempUser boss entites
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/admin/boss/list') }}">
                        Show
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Employees list</h4>
                <p class="card-text text-center">
                    View with employees list
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/admin/employee/list') }}">
                        Show
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Users list</h4>
                <p class="card-text text-center">
                    View with list of active users and TempUser user entites
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/admin/user/list') }}">
                        Show
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">All Properties</h4>
                <p class="card-text text-center">
                    View with list of all created and temporary properties
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/property/index') }}">
                        Show
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Subscriptions</h4>
                <p class="card-text text-center">
                    View with list of created subscriptions
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('/subscription/index') }}">
                        Show
                    </a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Graphic requests</h4>
                <p class="card-text text-center">
                    View with all graphic requests made by bosses
                </p>
                <div class="text-center">
                    <a class="btn btn-success" href="{{ URL::to('admin/graphic-requests') }}">
                        Show
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection