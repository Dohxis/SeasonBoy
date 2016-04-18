@extends('master')

@section('bg')
    {{ $user->getBackground() }}
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.1.1/Chart.min.js"></script>
{!! $stats !!}}
{!! $armies !!}}
@endsection

@section('content')
    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Statistics</b></div>
                <div class="panel-body">
                    <div class="well well-sm">
                        <center style="color: #777;"><b>Achievements: </b>

                            <span class="label @if($user->getEnemyTiles() == 0) label-primary @else label-default @endif">Won</span>
                            <span class="label @if($user->getTiles() >= 12) label-warning @else label-default @endif">Half World</span>
                            <span class="label @if($user->getTiles() == 24) label-danger @else label-default @endif">Conquered the World</span>
                            <span class="label @if($user['round'] <= 15 && $user->getEnemyTiles() == 0) label-warning @else label-default @endif">Won 15</span>
                            <span class="label @if($user['round'] <= 10 && $user->getEnemyTiles() == 0) label-danger @else label-default @endif">Won 10</span>
                            <span class="label @if($user->getTotalArmies() > 49) label-warning @else label-default @endif">Army 50</span>
                            <span class="label @if($user->getTotalArmies() > 99) label-danger @else label-default @endif">Army 100</span>

                        </center>
                    </div>

                    <div class="row center-block">
                        <div class="col-xs-6"><center><label><center>Playing people</center><br><canvas id="pie" width="200px"></canvas></label></center></div>
                        <div class="col-xs-6"><center><label><center>Armies</center><br><canvas id="armies" width="200px"></canvas></label></center></div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hero</th>
                                <th>Points</th>
                                <th>Achievements</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $index => $player)
                                <tr>
                                    <td scope="row">{{ $index + 1 }}</td>
                                    <td scope="row">{{ $player['name'] }} @if($player->getEnemyTiles() == 0)<span class="label label-primary">Won</span>@endif
                                    @if($player->getTiles() == 0)<span class="label label-danger">Lost</span>@endif
                                        @if($player->getTiles() >= 12)<span class="label label-warning">Half World</span>
                                        @elseif($player->getTiles() == 24)<span class="label label-danger">Conquered the World</span>@endif
                                    @if($player['round'] <= 15 && $player->getEnemyTiles() == 0)<span class="label label-warning">Won 15</span>
                                    @elseif($player['round'] <= 10 && $player->getEnemyTiles() == 0)<span class="label label-danger">Won 10</span>@endif
                                        @if($player->getTotalArmies() > 49)<span class="label label-warning">Army 50</span>
                                        @elseif($player->getTotalArmies() > 99)<span class="label label-danger">Army 100</span>@endif</td>
                                    <td scope="row">{{ $player['points'] }}</td>
                                    <td scope="row">{{ $player->getTiles() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection