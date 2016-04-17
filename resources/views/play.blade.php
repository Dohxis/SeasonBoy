@extends('master')

@section('bg')
    {{ $user->getBackground() }}
@endsection

@section('content')
    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-body">

                    @if($user->getEnemyTiles() == 0)
                        <div class="alert alert-success" role="alert" style="margin-bottom: 0px;">
                            <b>You won!!</b><br>
                            Congrats you won! You captured all of your enemy's territories.
                        </div><br>
                    @endif

                    @if($user['units'] > 0)
                    <div class="alert alert-warning" role="alert" style="margin-bottom: 0px;">
                        <b>You have {{ $user['units'] }} undeployed units!</b><br>
                        All units has to be deployed before attacking. To deploy click on your territories(Green tiles).
                    </div>
                    @else
                    <div class="alert alert-danger" role="alert" style="margin-bottom: 0px;">
                        <b>It's time to attack!</b><br>
                        Select attacking territory first, and then neutral or enemy's territory to attack.
                        @if(Session::has('attack'))
                            <br><br>
                            <b>Attacks:</b>
                            <ul>
                                @for($i = 0; $i < count(Session::get('attack')); $i += 2)
                                {{ App\Board::getArmies(Session::get('attack')[$i]) }} vs
                                {{ App\Board::getArmies(isset(Session::get('attack')[$i+1]) ? Session::get('attack')[$i+1] : '-1') }}<br>
                                @endfor
                            </ul>
                        @endif
                    </div>
                    @endif


                        <div class="row" style="margin: 5px;">
                            <div class="col-xs-6"><a href="/stats" style="width: 50%" class="center-block btn btn-primary">Statistics</a></div>
                            <div class="col-xs-6"><center>
                                    <ol class="breadcrumb" style="margin: 0px;">
                                        <li><b>Season buff</b></li>
                                        @if(Auth::user()->Summer)<li><span class="label label-warning">Summer</span></li>@endif
                                        @if(Auth::user()->Winter)<li><span class="label label-primary">Winter</span></li>@endif
                                        @if(Auth::user()->Spring)<li><span class="label label-success">Spring</span></li>@endif
                                        @if(Auth::user()->Autumn)<li><span class="label label-danger">Autumn</span></li>@endif
                                    </ol>
                                </center></div>
                        </div>


                    <nav class="navbar navbar-default">
                        <div class="container-fluid">

                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>

                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <p class="navbar-text">Hero <b>{{ $user['name'] }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season <b>{{ $user['season'] }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Turn <b>{{ $user['turn'] }}</b></p>
                                <ul class="nav navbar-nav navbar-right" style="width: 25%;">
                                    @if($user['units'] == 0)
                                        <a href="/next" style="margin: 8px; width: 100%" class="btn btn-danger">Next turn</a>
                                    @else
                                        <a href="/play" style="margin: 8px; width: 100%" class="btn btn-danger" disabled="disabled">Deploy army to continue</a>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <center>
                    <div class="board center-block">
                        <center class="center-things">
                        @foreach($tiles as $tile)
                            <a href="go/{{ $tile['tile'] }}" class="col-xs-1 tile hover-green" style="color: white; background-color: {{ $tile->getColor() }}; margin: 2px; height: 50px; width: 8%; padding-top: 3%"><b>{{ $tile['army'] }}</b></a>
                            @if(($tile['tile'] + 1) % 10 == 0)
                                <div class="row"></div>
                            @endif
                        @endforeach
                        </center>
                    </div>
                    </center>
                </div>
            </div>
        </div>
    </div>
@endsection