@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(auth()->check())
                        @if(auth()->user()->type == 'admin')
                        <passport-clients class="mb-4"></passport-clients>
                        <passport-authorized-clients class="mb-4"></passport-authorized-clients>
                        <passport-personal-access-tokens></passport-personal-access-tokens>
                        @else
                            You are logged in!
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
