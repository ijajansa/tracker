@extends('layouts.app')

@section('title', 'Change MPIN | Bachat')
@section('header', 'Change MPIN')

@section('content')
    <section class="glass-card">
        <p class="section-kicker">Security</p>
        <h2 class="section-title">Choose a new lock</h2>

        <form class="form-stack" method="POST" action="{{ route('settings.change-mpin') }}">
            @csrf

            @include('partials.pin-group', ['label' => 'Current MPIN', 'name' => 'current_mpin', 'groupId' => 'current-mpin'])
            @include('partials.pin-group', ['label' => 'New MPIN', 'name' => 'mpin', 'groupId' => 'new-mpin'])
            @include('partials.pin-group', ['label' => 'Confirm new MPIN', 'name' => 'mpin_confirmation', 'groupId' => 'confirm-new-mpin'])

            <button class="btn btn-primary btn-block" type="submit">Update MPIN</button>
        </form>
    </section>
@endsection
