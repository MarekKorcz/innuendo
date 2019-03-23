@extends('layouts.app')
@section('content')

{!! Html::script('js/boss_dashboard.js') !!}

<div class="container">
    
    <div class="jumbotron" style="margin-top: 30px;">
        @if (count($codes))    
            @for ($i = 1; $i <= count($codes); $i++)
                @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
                    <div class="row padding">
                        @include('boss.code_tile')
                @elseif ($i % 3 == 0)
                        @include('boss.code_tile')
                    </div>
                @else
                    @include('boss.code_tile')
                @endif
            @endfor   

            @if (count($codes) % 3 != 0)
                </div>
            @endif
        @endif
        
        <div class="text-center">
            
            
            
        </div>
    </div>
    
    <div class="jumbotron">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Twoje lokalizacje</h4>
                        <p class="card-text text-center">
                            Widok Twoich lokalizacji z listą pracowników do nich przynależących
                        </p>
                        <div class="text-center">
                            <a class="btn btn-success btn-lg" href="{{ URL::to('boss/property/list/') }}">
                                Pokaż
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-lg-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Twoje wykupione subskrypcje</h4>
                        <p class="card-text text-center">
                            Widok wykupionych subskrypcji ze szczegółowymi informacjami ich dotyczącymi oraz listą pracowników z nich korzystających
                        </p>
                        <div class="text-center">
<!--                            <a class="btn btn-success btn-lg" href="{{ URL::to('boss/dashboard/') }}">
                                Pokaż
                            </a>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection