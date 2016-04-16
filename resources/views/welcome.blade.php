@extends('master')

@section('content')
    <div class="row vcenter">
        <div class="col-md-6 center-block">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if(Session::has('error_login'))
                        <div class="alert alert-danger" role="alert"><small><b>Error:</b> Player with this name is taken or you entered a bad password!</small></div>
                    @else
                        <div class="alert alert-warning" role="alert"><small>You do not need to register, just pick a username and if it's not taken your hero will be created!</small></div>
                    @endif
                    <form method="post" action="/">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <button type="submit"  style="width: 50%" class="center-block btn btn-success">Play</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection