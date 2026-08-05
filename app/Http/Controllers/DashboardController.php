<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Services\FinanceService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(FinanceService $finance)
    {
        $user = auth()->user();
        $totals = $finance->totals($user);

        return view('dashboard.index', [
            'totals' => $totals,
            'recentTransactions' => $finance->recent($user),
            'hasTransactions' => Transaction::forUser($user->id)->exists(),
            'currentMonth' => now()->format('F Y'),
            'expenseCategories' => Category::active()->forType('expense')->orderBy('name')->get(),
            'incomeCategories' => Category::active()->forType('income')->orderBy('name')->get(),
        ]);
    }

    public function openingBalance()
    {
        $user = auth()->user();

        if (Transaction::forUser($user->id)->exists()) {
            return redirect()->route('dashboard');
        }

        return view('onboarding.opening-balance');
    }

    public function storeOpeningBalance(Request $request)
    {
        $user = auth()->user();

        if ($request->boolean('skip')) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0'],
        ]);

        if (Transaction::forUser($user->id)->exists()) {
            return redirect()->route('dashboard');
        }

        $category = Category::query()->firstOrCreate(
            ['type' => 'income', 'name' => 'Opening Balance'],
            ['icon' => '💰', 'is_default' => true, 'status' => 'active']
        );

        Transaction::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'type' => 'income',
            'amount' => $validated['amount'],
            'description' => 'Opening Balance',
            'transaction_date' => now()->toDateString(),
        ]);

        return redirect()->route('dashboard')->with('status', 'Opening balance saved.');
    }
}
