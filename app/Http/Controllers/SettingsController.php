<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeMpinRequest;
use App\Models\UserSetting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SettingsController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $settings = UserSetting::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['currency' => 'INR', 'currency_symbol' => '₹', 'theme' => 'light', 'auto_lock_minutes' => 5]
        );

        return view('settings.index', [
            'settings' => $settings,
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $settings = UserSetting::query()->firstOrCreate(
            ['user_id' => auth()->id()],
            ['currency' => 'INR', 'currency_symbol' => '₹', 'theme' => 'light', 'auto_lock_minutes' => 5]
        );

        $data = $request->validate([
            'currency' => ['required', 'string', 'max:10'],
            'currency_symbol' => ['required', 'string', 'max:10'],
            'theme' => ['required', 'in:light,dark,system'],
            'auto_lock_minutes' => ['required', 'integer', 'min:1', 'max:120'],
        ]);

        $settings->update($data);

        return back()->with('status', 'Settings updated.');
    }

    public function editMpin()
    {
        return view('settings.change-mpin');
    }

    public function updateMpin(ChangeMpinRequest $request)
    {
        $user = auth()->user();

        if (! Hash::check($request->string('current_mpin')->toString(), $user->mpin)) {
            return back()->withErrors([
                'current_mpin' => 'Current MPIN is incorrect.',
            ]);
        }

        $user->forceFill([
            'mpin' => $request->string('mpin')->toString(),
            'failed_attempts' => 0,
            'locked_until' => null,
        ])->save();

        return back()->with('status', 'MPIN updated successfully.');
    }

    public function export(): StreamedResponse
    {
        $user = auth()->user();
        $filename = 'bachat-transactions-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($user) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Type', 'Category', 'Amount', 'Description', 'Payment Method', 'Notes']);

            Transaction::forUser($user->id)
                ->with('category')
                ->orderByDesc('transaction_date')
                ->orderByDesc('id')
                ->chunk(200, function ($rows) use ($handle) {
                    foreach ($rows as $row) {
                        fputcsv($handle, [
                            $row->transaction_date?->format('Y-m-d'),
                            $row->type,
                            $row->category?->name,
                            $row->amount,
                            $row->description,
                            $row->payment_method,
                            $row->notes,
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been logged out.');
    }
}
