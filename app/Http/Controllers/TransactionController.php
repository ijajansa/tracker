<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use App\Services\FinanceService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request, FinanceService $finance)
    {
        return view('transactions.index', [
            'transactions' => $finance->filteredTransactions(auth()->user(), $request->all(), 10),
            'categories' => Category::active()->orderBy('type')->orderBy('name')->get(),
        ]);
    }

    public function create(Request $request)
    {
        $type = $request->string('type')->toString() ?: 'expense';

        return view('transactions.create', [
            'transaction' => new Transaction(['type' => $type, 'transaction_date' => now()->toDateString()]),
            'categories' => Category::active()->forType($type)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        $transaction = auth()->user()->transactions()->create($request->validated());

        return redirect()->route('transactions.index')->with('status', ucfirst($transaction->type).' saved.');
    }

    public function edit(Transaction $transaction)
    {
        abort_unless($transaction->user_id === auth()->id(), 403);

        return view('transactions.edit', [
            'transaction' => $transaction,
            'categories' => Category::active()->forType($transaction->type)->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        abort_unless($transaction->user_id === auth()->id(), 403);

        $transaction->update($request->validated());

        return redirect()->route('transactions.index')->with('status', 'Transaction updated.');
    }

    public function destroy(Transaction $transaction)
    {
        abort_unless($transaction->user_id === auth()->id(), 403);

        $summary = $transaction->description.' '.($transaction->type === 'income' ? '+' : '-').number_format((float) $transaction->amount, 2);
        $transaction->delete();

        return back()->with('status', 'Deleted '.$summary);
    }
}
