@extends('layouts.app')
@section('content')

{!! Html::style('css/welcome.css') !!}

    <div class="container-fluid">
        <div class="row jumbotron">
            <div class="col-12">
                <h2 class="text-center">@lang('welcome.discounts')</h2>
            </div>
        </div>
    </div>

    <div class="container">
        <h3 class="text-center">@lang('welcome.discount_paragraph')</h3>
        <div class="row text-center padding">
            @foreach ($discounts as $key => $discount)
                <div class="col-xs-12 col-sm-6 col-md-6">
                    @if ($key == 0)
                        @svg('regular/smile-beam')
                        <h3>
                            @if (Session('locale') == "en")
                                {{$discount->description_en}}
                            @else
                                {{$discount->description}}
                            @endif
                        </h3>
                    @elseif ($key == 1)
                        @svg('regular/laugh-beam')
                        <h3>
                            @if (Session('locale') == "en")
                                {{$discount->description_en}}
                            @else
                                {{$discount->description}}
                            @endif
                        </h3>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="row text-center padding">
            @foreach ($discounts as $key => $discount)
                <div class="col-xs-12 col-sm-6 col-md-6">
                    @if ($key == 2)
                        @svg('regular/grin-hearts')
                        <h3>
                            @if (Session('locale') == "en")
                                {{$discount->description_en}}
                            @else
                                {{$discount->description}}
                            @endif
                        </h3>
                    @elseif ($key == 3)
                        @svg('regular/surprise')
                        <h3>
                            @if (Session('locale') == "en")
                                {{$discount->description_en}}
                            @else
                                {{$discount->description}}
                            @endif
                        </h3>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

@endsection