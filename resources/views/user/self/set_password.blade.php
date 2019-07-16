@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="col-md-6 offset-md-3">

            <div class="card">
                <h5 class="card-header">
                    Set your password
                </h5>

                <div class="card-body">
                    Hello {{ $user->name }}, looks like you haven't set a password yet. Lets set one before proceeding.

                    <form action="{{ route('me.save-password') }}" method="post" class="mt-4">
                        {{ csrf_field() }}

                        <div class="form-group row">
                            <label for="password" class="col-md-3">Password</label>
                            <div class="col-md-9">
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" />

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password_confirmation" class="col-md-3">Confirm Password</label>
                            <div class="col-md-9">
                            <input type="password" name="password_confirmation" class="form-control" />
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-primary" value="Save" />
                            </div>
                        </div>


                    </form>
                </div>

            </div>

        </div>

    </div>
@endsection
