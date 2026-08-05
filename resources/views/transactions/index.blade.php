@extends('layouts.app')

@section('title', 'Transactions | Bachat')
@section('header', 'Transactions')

@section('content')
    <section class="glass-card">
        <div class="section-head">
            <div>
                <p class="section-kicker">History</p>
                <h2 class="section-title">Track every rupee</h2>
            </div>
            <a class="btn btn-secondary" href="{{ route('transactions.create') }}">Add</a>
        </div>

        <form class="filter-grid" method="GET" action="{{ route('transactions.index') }}">
            <div class="field">
                <label class="field-label" for="search">Search</label>
                <input id="search" class="control" name="search" type="search" value="{{ request('search') }}" placeholder="Description, notes, amount, category">
            </div>
            <div class="field">
                <label class="field-label" for="type">Type</label>
                <select id="type" class="control" name="type">
                    <option value="">All</option>
                    <option value="income" @selected(request('type') === 'income')>Income</option>
                    <option value="expense" @selected(request('type') === 'expense')>Expense</option>
                </select>
            </div>
            <div class="field">
                <label class="field-label" for="period">Period</label>
                <select id="period" class="control" name="period">
                    <option value="">All time</option>
                    <option value="today" @selected(request('period') === 'today')>Today</option>
                    <option value="week" @selected(request('period') === 'week')>This week</option>
                    <option value="month" @selected(request('period') === 'month')>This month</option>
                    <option value="custom" @selected(request('period') === 'custom')>Custom</option>
                </select>
            </div>
            <div class="field">
                <label class="field-label" for="category_id">Category</label>
                <select id="category_id" class="control" name="category_id">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label class="field-label" for="from">From</label>
                <input id="from" class="control" name="from" type="date" value="{{ request('from') }}">
            </div>
            <div class="field">
                <label class="field-label" for="to">To</label>
                <input id="to" class="control" name="to" type="date" value="{{ request('to') }}">
            </div>
            <button class="btn btn-primary" type="submit">Filter</button>
        </form>
    </section>

    <section class="glass-card">
        <div class="stack-list">
            @forelse($transactions as $transaction)
                <article class="transaction-row">
                    <div class="transaction-row__meta">
                        <span class="transaction-icon">{{ $transaction->category?->icon ?? ($transaction->type === 'income' ? '↗' : '↙') }}</span>
                        <div>
                            <strong>{{ $transaction->description }}</strong>
                            <p>{{ $transaction->category?->name ?? 'Uncategorized' }} · {{ $transaction->transaction_date?->format('d M, Y') }}</p>
                            <small class="muted">{{ $transaction->payment_method ?: 'No payment method' }}</small>
                        </div>
                    </div>
                    <div class="transaction-actions">
                        <strong class="transaction-row__amount {{ $transaction->type === 'income' ? 'is-income' : 'is-expense' }}">
                            {{ $transaction->type === 'income' ? '+' : '-' }}{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format((float) $transaction->amount, 2) }}
                        </strong>
                        <div class="row-actions">
                            <a class="text-link" href="{{ route('transactions.edit', $transaction) }}">Edit</a>
                            <form method="POST" action="{{ route('transactions.destroy', $transaction) }}">
                                @csrf
                                @method('DELETE')
                                <button class="text-link text-link--danger" type="submit" data-confirm-delete="Delete {{ $transaction->description }} {{ $transaction->type === 'income' ? '+' : '-' }}{{ number_format((float) $transaction->amount, 2) }}?">Delete</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <p class="muted">No transactions found.</p>
            @endforelse
        </div>

        <div class="pagination-wrap">
            {{ $transactions->links() }}
        </div>
    </section>
@endsection
