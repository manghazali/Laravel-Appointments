@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card-group">
            <div class="card p-4">
                <div class="card-body">
                    @if(\Session::has('message'))
                        <p class="alert alert-info">
                            {{ \Session::get('message') }}
                        </p>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        @if(request()->has('redirect'))
                            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                        @endif
                        <div class="row justify-content-center mb-3">
                            <div class="col">
                                <img src="{{ asset('img/logo.png') }}" style="max-width: 85%; height: auto;" />
                            </div>
                            <div class="col">
                                <img src="{{ asset('img/teraju-logo.png') }}" style="max-width: 100%; height: auto;" />
                            </div>
                        </div>
                        <p>&nbsp;</p>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-user"></i>
                                </span>
                            </div>
                            <input name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email', null) }}">
                            @if($errors->has('email'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email') }}
                                </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}">
                            <div class="input-group-append">
                                <span class="input-group-text" style="cursor:pointer;" onclick="togglePassword()">
                                    <i class="fa fa-eye" id="togglePasswordIcon"></i>
                                </span>
                            </div>
                            @if($errors->has('password'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('password') }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="row">
                            <div class="col-12 text-right">
                                <a class="btn px-0 text-muted" href="{{ route('password.request') }}" style="font-size: 0.7rem;">
                                    {{ trans('global.forgot_password') }}
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-blue btn-block btn-flat">
                                    {{ trans('global.login') }}
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                @if(Route::has('register')) 
                                <span class="text-muted" style="font-size: 0.8rem;">Don&#39;t have an account? 
                                    <a href="{{ route('register') }}">
                                        <strong>Sign Up</strong></a> 
                                </span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    function togglePassword() {
        var input = document.getElementById('password');
        var icon = document.getElementById('togglePasswordIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>