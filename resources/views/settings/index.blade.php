@extends('layouts.app')

@section('title', 'Settings | Bachat')
@section('header', 'Settings')

@section('content')
    <section class="glass-card">
        <p class="section-kicker">Account</p>
        <h2 class="section-title">{{ $user->full_name }}</h2>
        <p class="muted">Manage appearance, safety, and export tools.</p>
    </section>

    <section class="glass-card">
        <div class="section-head">
            <div>
                <p class="section-kicker">Preferences</p>
                <h3 class="section-title">App settings</h3>
            </div>
        </div>

        <form class="form-stack" method="POST" action="{{ route('settings.update') }}">
            @csrf
            <div class="quick-grid">
                <div class="field">
                    <label class="field-label" for="currency">Currency</label>
                    <input id="currency" class="control" name="currency" value="{{ old('currency', $settings->currency) }}" maxlength="10">
                </div>
                <div class="field">
                    <label class="field-label" for="currency_symbol">Currency symbol</label>
                    <input id="currency_symbol" class="control" name="currency_symbol" value="{{ old('currency_symbol', $settings->currency_symbol) }}" maxlength="10">
                </div>
            </div>

            <div class="quick-grid">
                <div class="field">
                    <label class="field-label" for="theme">Theme</label>
                    <select id="theme" class="control" name="theme">
                        <option value="light" @selected(old('theme', $settings->theme) === 'light')>Light</option>
                        <option value="dark" @selected(old('theme', $settings->theme) === 'dark')>Dark</option>
                        <option value="system" @selected(old('theme', $settings->theme) === 'system')>System</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field-label" for="auto_lock_minutes">Auto-lock minutes</label>
                    <input id="auto_lock_minutes" class="control" name="auto_lock_minutes" type="number" min="1" max="120" value="{{ old('auto_lock_minutes', $settings->auto_lock_minutes) }}">
                </div>
            </div>

            <button class="btn btn-primary btn-block" type="submit">Save settings</button>
        </form>
    </section>

    <section class="glass-card">
        <div class="section-head">
            <div>
                <p class="section-kicker">Security</p>
                <h3 class="section-title">Protect your tracker</h3>
            </div>
        </div>

        <div class="stack-list">
            <a class="action-card" href="{{ route('settings.change-mpin') }}">
                <strong>Change MPIN</strong>
                <span>Refresh your 4-digit lock code.</span>
            </a>
            <a class="action-card" href="{{ route('settings.export') }}">
                <strong>Export transactions</strong>
                <span>Download a CSV of your tracker history.</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="action-card action-card--button" type="submit">
                    <strong>Logout</strong>
                    <span>Clear the session and return to login.</span>
                </button>
            </form>
            <div class="action-card action-card--muted">
                <strong>Reset application data</strong>
                <span>Keep this for a future controlled reset flow.</span>
            </div>
        </div>
    </section>
@endsection
