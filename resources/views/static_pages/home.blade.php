@extends('layouts.default')
@section('title', 'Home')



@section('content')
    @if (Auth::check())

        <div class="row">
            <div class="col-md-8">
                <section class="status_form">
                    @include('shared._status_form')
                </section>
            </div>
        </div>

        <aside class="col-md-4">
            <section class="user_info">
                @include('shared._user_info',['user' => Auth::user()] )
            </section>
        </aside>
    @else

    <div class="jumbotron">
    <h1>HOME</h1>
        <p class="lead">
            This is a New Begin!
        </p>
        <p>
            Let's Do It ！
        </p>
        <p>
            <a href="{{ route('register') }}" class="btn btn-lg btn-success" role="button">Login Up</a>
        </p>

    </div>
    @endif
@stop