@extends('master')

@section('bg')
    {{ $user->getBackground() }}
@endsection

@section('content')
    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-body" style="padding-bottom: 50px;">

                    @if($user->getEnemyTiles() == 0)
                        <div class="alert alert-success" role="alert" style="margin-bottom: 0px;">
                            <b>You won!!</b><br>
                            Congrats you won! You captured all of your enemy's territories.
                        </div><br>
                    @endif

                    @if($user->getTiles() == 0)
                        <div class="alert alert-danger" role="alert" style="margin-bottom: 0px;">
                            <b>You lost!!</b><br>
                            You just lost, if you want can click <a href="/logout">here</a> to create a new hero.
                        </div><br>
                    @endif

                    @if($user['units'] > 0)
                    <div class="alert alert-warning" role="alert" style="margin-bottom: 0px;">
                        <b>You have {{ $user['units'] }} undeployed units!</b><br>
                        All units has to be deployed before attacking. To deploy click on your territories.
                    </div>
                    @else
                    <div class="alert alert-warning" role="alert" style="margin-bottom: 0px;">
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


                        <div class="well well-sm" style="margin-top: 5px; margin-bottom: 5px;"><center>

                            @if(Auth::user()->Summer)<span class="label label-warning">Summer</span>@endif
                            @if(Auth::user()->Winter)<span class="label label-primary">Winter</span>@endif
                            @if(Auth::user()->Spring)<span class="label label-success">Spring</span>@endif
                            @if(Auth::user()->Autumn)<span class="label label-danger">Autumn</span>@endif

                        </center></div>

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
                                <p class="navbar-text"><i class="fa fa-user" aria-hidden="true"></i> <b>{{ $user['name'] }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-sun-o" aria-hidden="true"></i>  {!! $user->getSeason() !!} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-compass" aria-hidden="true"></i>  <b>{{ $user['turn'] }}</b></p>
                                <ul class="nav navbar-nav navbar-right">
                                        <a href="/stats"  class="btn btn-primary navbar-btn">Statistics</a>
                                    @if($user['units'] == 0)
                                        <a href="/next"  class="btn btn-danger navbar-btn">Next turn</a>
                                    @else
                                        <a href="/play"  class="btn btn-danger navbar-btn" disabled="disabled">Deploy army to continue</a>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <center>
                    <div class="board center-block" style="padding-left: 9%; padding-right: 9%;">
                        <center class="center-things">
                            <div style="float: left; width: 600px;">
                                <div class="hex-row">
                                    <?php $add=1; ?>
                                    @foreach($tiles as $tile)

                                        <a href="go/{{ $tile['tile'] }}"></ahref> <div class="hex"><div class="top" style="border-bottom: 30px solid {{ $tile->getColor() }};"></div><div class="middle"
                                            style="color: white; background: {{ $tile->getColor() }}; padding: 20%;"><b>{{ $tile['army'] }}</b></div><div class="bottom" style="border-top: 30px solid {{ $tile->getColor() }};"></div></div></a>

                                        @if(($tile['tile'] + 1) % 5 == 0 && $add == 1)
                                </div>
                                <div class="hex-row even">
                                    <?php $add = 0; ?>

                                    @else

                                        @if(($tile['tile'] + 1) % 5 == 0 && $add == 0)
                                </div>
                                <div class="hex-row">
                                    <?php $add=1; ?>
                                    @endif

                                    @endif

                                    @endforeach
                                </div>
                        </center>
                    </div>
                    </center>

                </div>
            </div>
        </div>
    </div>
@endsection