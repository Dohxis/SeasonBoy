@extends('master')

@section('bg')
    {{ $user->getBackground() }}
@endsection

@section('scripts')
    <script>

        function testAnim(x) {
            $('.hexLink').click(function(){
                $(this).removeClass().addClass('hex animated ' + x).one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){});
                window.location = $(this).attr('href');
            });
        }

        $(document).ready(function(){
            $('.hexLink').click(function(e){
                e.preventDefault();
                var anim = 'shake';
                testAnim(anim);
            });
        });


    </script>
    @if(!$user['tutorial'])
        <script>
            $('#myModal').modal('show');
        </script>
    @endif

    @if(!$user['steps'] && $user['tutorial'])
        <script src="https://cdn.jsdelivr.net/intro.js/2.0.0/intro.min.js"></script>
        <script>
            introJs().setOptions({ 'showStepNumbers': 'false' }).oncomplete(function() {
                window.location.replace("/endTutorial");
            }).start();
        </script>
    @endif

    @if($user->getEnemyTiles() == 0)
        <script>
            $('#wonModal').modal('show');
            $('#close').click(function(){
                $('#wonModal').modal('hide');
            });
        </script>
    @endif

    @if($user->getTiles() == 0)
        <script>
            $('#lostModal').modal('show');
            $('#close').click(function(){
                $('#lostModal').modal('hide');
            });
        </script>
    @endif

@endsection

@section('content')


    <div class="modal fade" id="wonModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="font-size: 19px;">
                        <b>You just won!</b> <br>
                    </div>
                    <div class="modal-body" style="font-size: 19px;">
                        Wow, You just won against evil Janesia tribe! Our planet will always remember your name!
                    </div>
                    <div class="modal-footer">
                        <a id="close" class="btn btn-primary btn-lg">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="lostModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="font-size: 19px;">
                        <b>You just lost!</b> <br>
                    </div>
                    <div class="modal-body" style="font-size: 19px;">
                        You just lost against evil Janesia tribe! Better luck next time, thanks for playing. To try one more time create a new here <a href="/logout">here</a>.
                    </div>
                    <div class="modal-footer">
                        <a id="close" class="btn btn-primary btn-lg">Close</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="vertical-alignment-helper">
            <div class="modal-dialog vertical-align-center" role="document">
                <div class="modal-content">
                    <div class="modal-body" style="font-size: 19px;">
                        Welcome to <b>Sezonia</b> hero!<br><br>
                        Please help us! We need you to lead our army against the evil Janesia tribe! If we lose, all
                        seasons will be gone forever and our planet will die! Only you can win against this unstoppable power!
                        To do so you'll need to destroy all enemy's tiles, which are represented in red on our map. But first I will
                        introduce you with some basics, and if its not be enough for you I will give random tips throughout the game.<br>
                        <br>
                        <b>Good luck!</b>
                    </div>
                    <div class="modal-footer">
                        <a href="/startTutorial" type="button" class="btn btn-success btn-lg">Let's begin!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-body" style="padding-bottom: 50px;">

                    @if($user['units'] > 0)
                    <div class="alert alert-warning" role="alert" style="margin-bottom: 0px;" data-intro='This area is to show you what phase it is. It shows how much undeployed armies you have, and all of your attacks.' data-step='4'>
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


                        <div class="well well-sm" style="margin-top: 5px; margin-bottom: 5px;" data-intro='If you go through all of the season you will get extra armies two deploy, which can be huge advantage against your opponent.' data-step='5'><center>

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
                                <p class="navbar-text" data-intro='Help' data-step=''>Hero <b>{{ $user['name'] }}</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Season {!! $user->getSeason() !!} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Turn <b>{{ $user['turn'] }}</b></p>
                                <ul class="nav navbar-nav navbar-right">
                                        <a href="/stats"  class="btn btn-primary navbar-btn">Statistics</a>
                                    @if($user['units'] == 0)
                                        <a href="/next"  class="btn btn-danger navbar-btn">Next turn</a>
                                    @else
                                        <a href="/play"  class="btn btn-danger navbar-btn" disabled="disabled" data-intro='After deploying all of your armies and attacking your enemy you can click this button for next turn.' data-step='3'>Deploy army to continue</a>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <center>
                    <div class="board center-block" style="padding-left: 9%; padding-right: 9%;">
                        <center class="center-things">
                            <div style="float: left; width: 600px;" data-intro="This is your map. Each tile has it's color to identify who owns it. Enemy owns <b style='color:#C44D58;'>red</b> tiles, you own <b style='color:#519548;'>green</b>, and <b style='color:#556270;'>grey</b> is neutral. There are to extra tile types, for example occupying <b style='color:#E58D1E;'>orange</b> will grant you bonus army." data-step='1'>
                                <div class="hex-row">
                                    <?php $add=1; ?>
                                    @foreach($tiles as $tile)

                                        <a class="hexLink" href="/go/{{ $tile['tile'] }}"> <div @if($tile->getColor() == "#519548")data-intro="This is our starting territory. You have to deploy all your armies before attacking. To deploy army you have to click on your tile. After deploying all of your armies, attack phase will start. To attack click on your territory and then on enemy's or neutral one. (Sometimes you need to double click on the tile!)" data-step="2"@endif class="hex animated fadeIn"><div class="top" style="border-bottom: 30px solid {{ $tile->getColor() }};"></div><div class="middle"
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

                <div class="well well-sm" style="margin:20px;">
                    <center style="color: #777;"><b>Tip: {!! $tip !!}</b></center>
                </div>
            </div>
        </div>
    </div>
@endsection