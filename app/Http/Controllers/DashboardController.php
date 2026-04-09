<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalRevenue = $user->invoices()->where('status', '入金済')->sum('amount');
        $activeProjects = $user->projects()->where('status', '進行中')->count();
        $invoiceCount = $user->invoices()->whereMonth('issue_date', now()->month)->count();
        $unpaidAmount = $user->invoices()->whereIn('status', ['送付済', '未入金期限超過'])->sum('amount');

        // 過去6ヶ月の売上グラフ用データ
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenue[] = [
                'month' => $month->format('n月'),
                'amount' => $user->invoices()
                    ->where('status', '入金済')
                    ->whereYear('issue_date', $month->year)
                    ->whereMonth('issue_date', $month->month)
                    ->sum('amount'),
            ];
        }

        // 今月締切の進行中プロジェクト
        $upcomingProjects = $user->projects()
            ->with('client')
            ->where('status', '進行中')
            ->whereMonth('deadline', now()->month)
            ->orderBy('deadline')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalRevenue', 'activeProjects', 'invoiceCount', 'unpaidAmount',
            'monthlyRevenue', 'upcomingProjects'
        ));
    }
}
