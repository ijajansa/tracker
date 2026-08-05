@extends('layouts.app')

@section('title', 'Dashboard | Bachat')
@section('header', 'Dashboard')

@section('content')
    <section class="hero glass-card">
        <div>
            <p class="section-kicker">Hello, {{ auth()->user()->full_name }}</p>
            <h2 class="section-title">This month at a glance</h2>
            <p class="muted">{{ $currentMonth }}</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('transactions.create') }}">Add transaction</a>
    </section>

    <section class="metric-grid">
        <article class="metric-card">
            <span class="metric-label">Available balance</span>
            <strong class="metric-value">{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format($totals['balance'], 2) }}</strong>
        </article>
        <article class="metric-card">
            <span class="metric-label">Total income</span>
            <strong class="metric-value metric-value--income">+{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format($totals['income'], 2) }}</strong>
        </article>
        <article class="metric-card">
            <span class="metric-label">Total expenses</span>
            <strong class="metric-value metric-value--expense">-{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format($totals['expense'], 2) }}</strong>
        </article>
    </section>

    <section class="glass-card">
        <div class="section-head">
            <div>
                <p class="section-kicker">Quick entry</p>
                <h3 class="section-title">Add income or expense fast</h3>
            </div>
            <a class="text-link" href="{{ route('transactions.index') }}">History</a>
        </div>

        <form class="form-stack" method="POST" action="{{ route('transactions.store') }}" data-draft-form data-draft-key="dashboard-quick">
            @csrf
            <div class="quick-grid">
                <div class="field">
                    <label class="field-label" for="quick_type">Type</label>
                    <select id="quick_type" class="control" name="type">
                        <option value="expense" selected>Expense</option>
                        <option value="income">Income</option>
                    </select>
                </div>
                <div class="field">
                    <label class="field-label" for="quick_amount">Amount</label>
                    <input id="quick_amount" class="control control--large" name="amount" type="number" min="0" step="0.01" inputmode="decimal" placeholder="0.00" autofocus data-autofocus-amount>
                </div>
            </div>

            <div class="field">
                <label class="field-label" for="quick_description">Purpose / description</label>
                <input id="quick_description" class="control" name="description" type="text" maxlength="255" placeholder="Lunch, salary, rent, fuel...">
            </div>

            <div class="quick-grid">
                <div class="field">
                    <label class="field-label" for="quick_category_id">Category</label>
                    <select id="quick_category_id" class="control" name="category_id">
                        <option value="">Select category</option>
                        @foreach($expenseCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                        @foreach($incomeCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field-label" for="quick_date">Date</label>
                    <input id="quick_date" class="control" name="transaction_date" type="date" value="{{ now()->toDateString() }}">
                </div>
            </div>

            <details class="details-card">
                <summary>More details</summary>
                <div class="details-grid">
                    <div class="field">
                        <label class="field-label" for="quick_payment_method">Payment method</label>
                        <input id="quick_payment_method" class="control" name="payment_method" type="text" maxlength="50" placeholder="Cash, UPI, Card">
                    </div>
                    <div class="field">
                        <label class="field-label" for="quick_notes">Notes</label>
                        <textarea id="quick_notes" class="control control--textarea" name="notes" maxlength="1000" rows="3" placeholder="Optional note"></textarea>
                    </div>
                </div>
            </details>

            <button class="btn btn-primary btn-block" type="submit">Save transaction</button>
        </form>
    </section>

    <section class="glass-card">
        <div class="section-head">
            <div>
                <p class="section-kicker">Recent activity</p>
                <h3 class="section-title">Latest 5 transactions</h3>
            </div>
        </div>

        <div class="stack-list">
            @forelse($recentTransactions as $transaction)
                <article class="transaction-row">
                    <div class="transaction-row__meta">
                        <span class="transaction-icon">{{ $transaction->category?->icon ?? ($transaction->type === 'income' ? '↗' : '↙') }}</span>
                        <div>
                            <strong>{{ $transaction->description }}</strong>
                            <p>{{ $transaction->category?->name ?? 'Uncategorized' }} · {{ $transaction->transaction_date?->format('d M, Y') }}</p>
                        </div>
                    </div>
                    <div class="transaction-row__amount {{ $transaction->type === 'income' ? 'is-income' : 'is-expense' }}">
                        {{ $transaction->type === 'income' ? '+' : '-' }}{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format((float) $transaction->amount, 2) }}
                    </div>
                </article>
            @empty
                <p class="muted">No transactions yet. Add your opening balance to get started.</p>
            @endforelse
        </div>
    </section>
@endsection
