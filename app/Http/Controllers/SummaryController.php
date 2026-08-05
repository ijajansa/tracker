<?php

namespace App\Http\Controllers;

use App\Services\FinanceService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index(Request $request, FinanceService $finance)
    {
        $month = Carbon::parse(($request->get('month') ?: now()->format('Y-m')).'-01');

        return view('summary.index', $finance->monthlySummary(auth()->user(), $month));
    }
}
