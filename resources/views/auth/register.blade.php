@extends('layouts.auth')

@section('title', 'Register | Bachat')
@section('headline', 'Create your Bachat lock')
@section('subheadline', 'Register once with your name and a 4-digit MPIN.')

@section('content')
    <form class="glass-card form-stack" method="POST" action="{{ route('register') }}">
        @csrf

        <div class="field">
            <label class="field-label" for="full_name">Full name</label>
            <input id="full_name" class="control" name="full_name" type="text" value="{{ old('full_name') }}" maxlength="100" placeholder="Your full name" autofocus>
        </div>

        @include('partials.pin-group', [
            'label' => 'MPIN',
            'name' => 'mpin',
            'groupId' => 'register-mpin',
        ])

        @include('partials.pin-group', [
            'label' => 'Confirm MPIN',
            'name' => 'mpin_confirmation',
            'groupId' => 'register-mpin-confirm',
        ])

        <button class="btn btn-primary btn-block" type="submit">Register</button>
        <p class="muted center">Your MPIN is hashed and stored securely.</p>
    </form>
@endsection
