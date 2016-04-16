@extends('master')

@section('bg')
    {{ $user->getBackground() }}
@endsection

@section('content')
    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div class="alert alert-success" role="alert">
                        <b>You have {{ $user['units'] }} undeployed units!</b><br>
                        All units has to be deployed before attacking. To deploy click on your territories(Green tiles).
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
                                    <a href="/next" style="margin: 8px; width: 100%" class="btn btn-danger">Next turn</a>
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