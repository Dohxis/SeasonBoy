@extends('master')

@section('bg')
    {{ $user->getBackground() }}
@endsection

@section('content')
    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b>Select your primary season!</b>
                </div>
                <div class="panel-body" style="font-size: 18px;">
                    It's time to select your primary season. On your primary season you will get bonus army, so choose wisely!
                    <div class="row center-block" style="margin-top: 25px; margin-bottom: 25px;">
                        <div class="col-xs-3"><center><a href="/pickSeason/Winter">{!! $user->getSeasonLabel("Winter") !!}</a></center></div>
                        <div class="col-xs-3"><center><a href="/pickSeason/Spring">{!! $user->getSeasonLabel("Spring") !!}</a></center></div>
                        <div class="col-xs-3"><center><a href="/pickSeason/Summer">{!! $user->getSeasonLabel("Summer") !!}</a></center></div>
                        <div class="col-xs-3"><center><a href="/pickSeason/Autumn">{!! $user->getSeasonLabel("Autumn") !!}</a></center></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection