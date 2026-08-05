@extends('layouts.app')

@section('title', 'Summary | Bachat')
@section('header', 'Summary')

@section('content')
    @php
        $prevMonth = $month->copy()->subMonth()->format('Y-m');
        $nextMonth = $month->copy()->addMonth()->format('Y-m');
    @endphp

    <section class="glass-card">
        <div class="section-head">
            <div>
                <p class="section-kicker">Monthly view</p>
                <h2 class="section-title">{{ $month->format('F Y') }}</h2>
            </div>
            <div class="actions-row">
                <a class="btn btn-secondary" href="{{ route('summary', ['month' => $prevMonth]) }}">Prev</a>
                <a class="btn btn-secondary" href="{{ route('summary', ['month' => $nextMonth]) }}">Next</a>
            </div>
        </div>
    </section>

    <section class="metric-grid">
        <article class="metric-card">
            <span class="metric-label">Income</span>
            <strong class="metric-value metric-value--income">+{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format($income, 2) }}</strong>
        </article>
        <article class="metric-card">
            <span class="metric-label">Expense</span>
            <strong class="metric-value metric-value--expense">-{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format($expense, 2) }}</strong>
        </article>
        <article class="metric-card">
            <span class="metric-label">Balance</span>
            <strong class="metric-value">{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format($balance, 2) }}</strong>
        </article>
    </section>

    <section class="glass-card">
        <div class="section-head">
            <div>
                <p class="section-kicker">Highlights</p>
                <h3 class="section-title">How the month shaped up</h3>
            </div>
        </div>
        <div class="summary-grid">
            <div class="summary-tile">
                <span>Highest expense</span>
                <strong>{{ $highestExpense?->description ?? 'N/A' }}</strong>
                <small>{{ $highestExpense ? auth()->user()->settings()->firstOrCreate([])->currency_symbol.number_format((float) $highestExpense->amount, 2) : 'No expense yet' }}</small>
            </div>
            <div class="summary-tile">
                <span>Most used category</span>
                <strong>{{ $mostUsedCategory ?? 'N/A' }}</strong>
            </div>
            <div class="summary-tile">
                <span>Daily average expense</span>
                <strong>{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format($dailyAverageExpense, 2) }}</strong>
            </div>
        </div>
    </section>

    <section class="glass-card">
        <div class="section-head">
            <div>
                <p class="section-kicker">Category totals</p>
                <h3 class="section-title">Expense breakdown</h3>
            </div>
        </div>

        <div class="stack-list">
            @forelse($categoryTotals as $row)
                <div class="summary-bar">
                    <div class="summary-bar__top">
                        <strong>{{ $row['name'] }}</strong>
                        <span>{{ auth()->user()->settings()->firstOrCreate([])->currency_symbol }}{{ number_format($row['amount'], 2) }}</span>
                    </div>
                    <div class="summary-bar__track">
                        <span class="summary-bar__fill" style="width: {{ $expense > 0 ? min(100, ($row['amount'] / $expense) * 100) : 0 }}%"></span>
                    </div>
                </div>
            @empty
                <p class="muted">No expense categories for this month yet.</p>
            @endforelse
        </div>
    </section>
@endsection
