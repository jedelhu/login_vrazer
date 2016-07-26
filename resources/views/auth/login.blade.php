@extends('layouts.auth')
@section('content')
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form class="sign-box" role="form" method="POST" action="{{ url('/login') }}">
                    {!! csrf_field() !!}
                    <div class="sign-avatar">
                        <img src="{{asset('images/avatar-sign.png') }}">
                    </div>

                    <header class="sign-title">Sign In</header>


                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                        <input type="text" class="form-control" name="email" placeholder="Enter Email"
                               value="{{ old('email') }}">


                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" name="password" placeholder="Password">

                    </div>
                    <div class="form-group">
                        <div class="checkbox float-left">
                            <input type="checkbox" name="remember" id="signed-in"/>
                            <label for="signed-in">Keep me signed in</label>
                        </div>
                        <div class="float-right reset">
                            <a href="{{ url('/password/reset') }}">Reset Password</a>
                        </div>
                    </div>
                    @if ($errors->has('email'))
                        <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                            </span>
                    @endif
                    @if ($errors->has('password'))
                        <span class="help-block">
                                 <strong>{{ $errors->first('password') }}</strong>
                            </span>
                    @endif
                    <button type="submit" class="btn btn-rounded">Sign in</button>
                    <p class="sign-note">New to our website? <a href="{{ url('/register') }}">Sign up</a></p>
                </form>
            </div>
        </div>
    </div><!--.page-center-->
@endsection

<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>




