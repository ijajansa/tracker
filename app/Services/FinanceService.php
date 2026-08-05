<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FinanceService
{
    public function totals(User $user): array
    {
        $query = Transaction::forUser($user->id);

        $income = (float) (clone $query)->income()->sum('amount');
        $expense = (float) (clone $query)->expense()->sum('amount');

        return [
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
        ];
    }

    public function recent(User $user, int $limit = 5): Collection
    {
        return Transaction::forUser($user->id)
            ->with('category')
            ->latest('transaction_date')
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function filteredTransactions(User $user, array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = Transaction::forUser($user->id)->with('category');

        if ($search = trim((string) ($filters['search'] ?? ''))) {
            $query->where(function ($inner) use ($search) {
                $inner->where('description', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($category) use ($search) {
                        $category->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if (($type = $filters['type'] ?? null) && in_array($type, ['income', 'expense'], true)) {
            $query->where('type', $type);
        }

        if ($categoryId = $filters['category_id'] ?? null) {
            $query->where('category_id', $categoryId);
        }

        if (($period = $filters['period'] ?? null) && $period !== 'custom') {
            $today = Carbon::today();
            [$start, $end] = match ($period) {
                'today' => [$today->copy(), $today->copy()],
                'week' => [$today->copy()->startOfWeek(), $today->copy()->endOfWeek()],
                'month' => [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()],
                default => [null, null],
            };

            if ($start && $end) {
                $query->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()]);
            }
        }

        if ($from = $filters['from'] ?? null) {
            $query->whereDate('transaction_date', '>=', $from);
        }

        if ($to = $filters['to'] ?? null) {
            $query->whereDate('transaction_date', '<=', $to);
        }

        return $query->orderByDesc('transaction_date')->orderByDesc('id')->paginate($perPage)->withQueryString();
    }

    public function monthlySummary(User $user, Carbon $month): array
    {
        $start = $month->copy()->startOfMonth();
        $end = $month->copy()->endOfMonth();

        $transactions = Transaction::forUser($user->id)
            ->with('category')
            ->whereBetween('transaction_date', [$start->toDateString(), $end->toDateString()])
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();

        $income = (float) $transactions->where('type', 'income')->sum('amount');
        $expense = (float) $transactions->where('type', 'expense')->sum('amount');
        $expenseTransactions = $transactions->where('type', 'expense');
        $highestExpense = $expenseTransactions->sortByDesc('amount')->first();
        $mostUsedCategory = $expenseTransactions
            ->groupBy(fn ($transaction) => $transaction->category?->name ?? 'Uncategorized')
            ->sortByDesc(fn ($group) => $group->count())
            ->keys()
            ->first();

        $categoryTotals = $expenseTransactions
            ->groupBy(fn ($transaction) => $transaction->category?->name ?? 'Uncategorized')
            ->map(function ($group, $name) {
                return [
                    'name' => $name,
                    'amount' => (float) $group->sum('amount'),
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('amount')
            ->values();

        return [
            'month' => $month,
            'income' => $income,
            'expense' => $expense,
            'balance' => $income - $expense,
            'highestExpense' => $highestExpense,
            'mostUsedCategory' => $mostUsedCategory,
            'dailyAverageExpense' => $expense / max(1, $month->daysInMonth),
            'categoryTotals' => $categoryTotals,
            'transactions' => $transactions,
        ];
    }
}
