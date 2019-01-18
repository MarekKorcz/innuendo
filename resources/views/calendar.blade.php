@extends('layouts.app')

@section('content')
{!! Html::style('css/calendar.css') !!}

<div class="container">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
                        <th>Pon</th>
                        <th>Wt</th>
                        <th>Śr</th>
                        <th>Czw</th>
                        <th>Pt</th>
                        <th>Sob</th>
                        <th>Nd</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>29</td>
                        <td>30</td>
                        <td>31</td>
                        <td>1</td>
                        <td>2</td>
                        <td>3</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>6</td>
                        <td>7</td>
                        <td>8</td>
                        <td>9</td>
                        <td>10</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>13</td>
                        <td>14</td>
                        <td>15</td>
                        <td>16</td>
                        <td>17</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>19</td>
                        <td>20</td>
                        <td>21</td>
                        <td>22</td>
                        <td>23</td>
                        <td>24</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>26</td>
                        <td>27</td>
                        <td>28</td>
                        <td>29</td>
                        <td>30</td>
                        <td>1</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection