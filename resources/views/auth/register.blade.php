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
                    <form method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}
                        @if(request()->has('redirect'))
                            <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                        @endif
                        <div class="row justify-content-center mb-3">
                            <div class="col">
                                <img src="{{ asset('img/logo.png') }}" style="max-width: 80%; height: auto;" />
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
                            <input name="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" required autofocus placeholder="{{ trans('global.user_name') }}" value="{{ old('name', null) }}">
                            @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-id-card"></i>
                                </span>
                            </div>
                            <input name="nric" type="text" maxlength="12" onkeypress="return event.charCode >= 48 && event.charCode <= 57" inputmode="numeric" class="form-control{{ $errors->has('nric') ? ' is-invalid' : '' }}" required autofocus placeholder="{{ trans('global.nric') }}" value="{{ old('nric', null) }}">
                            @if($errors->has('nric'))
                            <div class="invalid-feedback">
                                {{ $errors->first('nric') }}
                            </div>
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-phone"></i>
                                </span>
                            </div>
                            <input name="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.phone') }}" value="{{ old('phone', null) }}">
                            @if($errors->has('phone'))
                            <div class="invalid-feedback">
                                {{ $errors->first('phone') }}
                            </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-envelope"></i>
                                </span>
                            </div>
                            <input name="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.register_email') }}" value="{{ old('email', null) }}">
                            @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-building"></i>
                                </span>
                            </div>
                            <input name="company_name" type="text" class="form-control{{ $errors->has('company_name') ? ' is-invalid' : '' }}" required autofocus placeholder="{{ trans('global.company_name') }}" value="{{ old('company_name', null) }}">
                            @if($errors->has('company_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('company_name') }}
                            </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.register_password') }}">
                            @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                            @endif
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-lock"></i></span>
                            </div>
                            <input name="password_confirmation" type="password" class="form-control" required placeholder="{{ trans('global.register_password_confirmation') }}">
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-blue btn-block btn-flat">
                                    {{ trans('global.register') }}
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                <span class="text-muted" style="font-size: 0.8rem;">Already have an account?
                                    <a href="{{ route('login') }}">
                                        <strong>Log In</strong></a>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection