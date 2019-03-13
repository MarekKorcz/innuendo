@extends('layouts.app')

@section('content')

{!! Html::style('css/subscription_list.css') !!}

<div class="container">

    <h1 class="text-center padding">Lista dostępnych pakietów</h2>
    
    <hr>

    @if (count($subscriptions))    
        @for ($i = 1; $i <= count($subscriptions); $i++)
            @if ($i == 1 || $i == 4 || $i == 7 || $i == 10)
                <div class="row padding">
                    <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                        <div class="card">
                            <div class="text-center">
                                @svg('brands/apple')
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{$subscriptions[$i - 1]->name}}</h5>
                                <p class="card-text">
                                    {!!$subscriptions[$i - 1]->description!!}
                                </p>
                                <div class="text-center">
                                    <a class="btn btn-success" href="{{ URL::to('user/subscription/show/' . $subscriptions[$i - 1]->slug) }}">
                                        Zobacz
                                    </a>                                    
                                </div>
                            </div>
                        </div>
                    </div>
            @elseif ($i % 3 == 0)
                    <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                        <div class="card">
                            <div class="text-center">
                                @svg('brands/apple')
                            </div>
                            <div class="card-body">
                                <h5 class="card-title text-center">{{$subscriptions[$i - 1]->name}}</h5>
                                <p class="card-text">
                                    {!!$subscriptions[$i - 1]->description!!}
                                </p>
                                <div class="text-center">
                                    <a class="btn btn-success" href="{{ URL::to('user/subscription/show/' . $subscriptions[$i - 1]->slug) }}">
                                        Zobacz
                                    </a>                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-xs-12 col-sm-6 col-lg-4 col-md-4">
                    <div class="card">
                        <div class="text-center">
                            @svg('brands/apple')
                        </div>
                        <div class="card-body">
                            <h5 class="card-title text-center">{{$subscriptions[$i - 1]->name}}</h5>
                            <p class="card-text">
                                {!!$subscriptions[$i - 1]->description!!}
                            </p>
                            <div class="text-center">
                                <a class="btn btn-success" href="{{ URL::to('user/subscription/show/' . $subscriptions[$i - 1]->slug) }}">
                                    Zobacz
                                </a>                                    
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endfor   

        @if (count($subscriptions) % 3 != 0)
            </div>
        @endif
    @endif
</div>
@endsection