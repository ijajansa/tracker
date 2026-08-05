@extends('layouts.app')

@section('title', 'Add Transaction | Bachat')
@section('header', 'Add transaction')

@section('content')
    <section class="glass-card">
        <p class="section-kicker">New entry</p>
        <h2 class="section-title">Record income or expense</h2>
        @include('transactions._form', [
            'action' => route('transactions.store'),
            'method' => null,
            'transaction' => $transaction,
            'categories' => $categories,
            'submitLabel' => 'Save transaction',
            'draftKey' => 'transaction-create',
        ])
    </section>
@endsection
