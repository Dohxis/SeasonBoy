@extends('master')

@section('content')
    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-body">

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
                        <center style="margin-left: 13%;">
                        @foreach($tiles as $tile)
                            <div class="col-md-1 tile" style="color: white; background-color: #3f5a54; margin: 2px; height: 50px; width: 8%;">{{ $tile['tile'] }}</div>
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