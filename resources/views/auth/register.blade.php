@extends('layouts.auth')

@section('content')
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">
                <form class="sign-box" role="form" method="POST" action="{{ url('/register') }}">
                    {!! csrf_field() !!}
                    <div class="sign-avatar no-photo">&plus;</div>
                    <header class="sign-title">Sign Up</header>
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                               placeholder="Name">

                        @if ($errors->has('name'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="text" class="form-control" name="email" value="{{ old('email') }}"
                               placeholder="E-Mail">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        <input type="password" class="form-control" name="password_confirmation"
                               placeholder="Repeat password">
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-rounded btn-success sign-up">Sign up</button>
                    <p class="sign-note">Already have an account? <a href="{{ url('/login') }}">Sign in</a></p>
                </form>
            </div>
        </div>
    </div><!--.page-center-->

@endsection
