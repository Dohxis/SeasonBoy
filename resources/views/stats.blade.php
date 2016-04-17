@extends('master')

@section('bg')
    {{ $user->getBackground() }}
@endsection

@section('content')
    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Hero</th>
                                <th>Points</th>
                                <th>Tiles occupied</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $index => $player)
                                <tr>
                                    <td scope="row">{{ $index + 1 }}</td>
                                    <td scope="row">{{ $player['name'] }} @if($player->getEnemyTiles() == 0)<span class="label label-primary">Won</span>@endif
                                        @if($player->getTiles() == 0)<span class="label label-danger">Lost</span>@endif</td>
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