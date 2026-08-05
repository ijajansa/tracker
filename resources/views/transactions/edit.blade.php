@extends('layouts.app')

@section('title', 'Edit Transaction | Bachat')
@section('header', 'Edit transaction')

@section('content')
    <section class="glass-card">
        <p class="section-kicker">Update entry</p>
        <h2 class="section-title">Make your changes</h2>
        @include('transactions._form', [
            'action' => route('transactions.update', $transaction),
            'method' => 'PUT',
            'transaction' => $transaction,
            'categories' => $categories,
            'submitLabel' => 'Update transaction',
            'draftKey' => 'transaction-edit-'.$transaction->id,
        ])
    </section>
@endsection
