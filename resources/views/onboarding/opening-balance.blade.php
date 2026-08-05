@extends('layouts.app')

@section('title', 'Opening Balance | Bachat')
@section('header', 'Opening Balance')

@section('content')
    <section class="glass-card">
        <p class="section-kicker">Set your starting point</p>
        <h2 class="section-title">How much money is available right now?</h2>
        <p class="muted">This becomes your first income transaction, so the balance starts cleanly.</p>

        <form class="form-stack" method="POST" action="{{ url('/opening-balance') }}" data-draft-form data-draft-key="opening-balance">
            @csrf
            <div class="field">
                <label class="field-label" for="opening_amount">Opening balance</label>
                <input id="opening_amount" class="control control--large" name="amount" type="number" min="0" step="0.01" inputmode="decimal" placeholder="0.00" autofocus data-autofocus-amount>
            </div>
            <div class="actions-row">
                <button class="btn btn-primary" type="submit">Save</button>
                <button class="btn btn-secondary" type="submit" name="skip" value="1">Skip</button>
            </div>
        </form>
    </section>
@endsection
