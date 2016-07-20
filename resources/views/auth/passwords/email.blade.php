@extends('layouts.auth')

@section('content')
    <div class="page-center">
        <div class="page-center-in">
            <div class="container-fluid">

                <form class="sign-box reset-password-box" role="form" method="POST"  action="{{ url('/password/email') }}">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    {!! csrf_field() !!}
                    
                    <header class="sign-title">Reset Password</header>
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <input type="text" class="form-control" name="email" value="{{ $email or old('email') }}"
                               placeholder="Enter Email">
                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-rounded">Reset</button>
                    or <a href="{{ url('/login') }}">Sign in</a>
                </form>
            </div>
        </div>
    </div><!--.page-center-->
    @endsection

