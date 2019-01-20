@extends('layouts.app')

@section('content')
{!! Html::style('css/calendar.css') !!}

<div class="container">
    <div id="calendar" class="table-responsive">
        <div id="table-nav-bar">
            <div class="head-tile text-center" style="width: 25%;">
                @svg('solid/angle-left')
            </div>
            <div class="head-tile" style="width: 50%;">
                <div class="text-center">
                    <h2>2019</h2>
                    <h3>Kwiecień</h3>
                </div>
            </div>
            <div class="head-tile text-center" style="width: 25%;">
                @svg('solid/angle-right')
            </div>
            <div style="clear: both;"></div>
        </div>
        <table class="table">
            <thead>
                <tr id="days">
                    <th class="text-center">Pon</th>
                    <th class="text-center">Wt</th>
                    <th class="text-center">Śr</th>
                    <th class="text-center">Czw</th>
                    <th class="text-center">Pt</th>
                    <th class="text-center">Sob</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">29</td>
                    <td class="text-center">30</td>
                    <td class="text-center">31</td>
                    <td class="text-center">1</td>
                    <td class="text-center">2</td>
                    <td class="text-center">3</td>
                </tr>
                <tr>
                    <td class="text-center">5</td>
                    <td class="text-center">6</td>
                    <td class="text-center">7</td>
                    <td class="text-center">8</td>
                    <td class="text-center marked">9</td>
                    <td class="text-center">10</td>
                </tr>
                <tr>
                    <td class="text-center">12</td>
                    <td class="text-center">13</td>
                    <td class="text-center">14</td>
                    <td class="text-center">15</td>
                    <td class="text-center">16</td>
                    <td class="text-center">17</td>
                </tr>
                <tr>
                    <td class="text-center">19</td>
                    <td class="text-center">20</td>
                    <td class="text-center">21</td>
                    <td class="text-center">22</td>
                    <td class="text-center">23</td>
                    <td class="text-center">24</td>
                </tr>
                <tr>
                    <td class="text-center">26</td>
                    <td class="text-center">27</td>
                    <td class="text-center">28</td>
                    <td class="text-center">29</td>
                    <td class="text-center">30</td>
                    <td class="text-center">1</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection