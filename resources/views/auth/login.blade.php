@extends('layouts.auth')

@section('title', 'Login | Bachat')
@section('headline', 'Unlock Bachat')
@section('subheadline', 'Enter your 4-digit MPIN to continue.')

@section('content')
    <form class="glass-card form-stack" method="POST" action="{{ route('login') }}">
        @csrf

        @include('partials.pin-group', [
            'label' => 'MPIN',
            'name' => 'mpin',
            'groupId' => 'login-mpin',
            'autoSubmit' => true,
        ])

        <div class="keypad" data-pin-keypad>
            @foreach([1,2,3,4,5,6,7,8,9,'back',0,'clear'] as $key)
                <button class="keypad__key" type="button" data-pin-key="{{ $key }}">{{ is_numeric($key) ? $key : ($key === 'back' ? '⌫' : 'Clear') }}</button>
            @endforeach
        </div>

        <button class="btn btn-primary btn-block" type="submit">Login</button>
        <p class="muted center">Auto-lock kicks in after five failed attempts.</p>
    </form>
@endsection
