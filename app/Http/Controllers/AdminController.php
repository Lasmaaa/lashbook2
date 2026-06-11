<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\LoyaltyScanLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $bookingsByDate = Booking::selectRaw('date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        return view('admin.index', compact('bookingsByDate'));
    }

    public function bookingsByDate($date)
    {
        $bookings = Booking::with(['user', 'procedure'])
            ->where('date', $date)
            ->orderBy('time')
            ->get();

        return view('admin.bookings-date', compact('bookings', 'date'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,arrived,no_show',
        ]);

        $booking->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Booking status updated.');
    }

    public function users()
    {
        $search = request('email');
        $users = User::query()
            ->when($search, fn ($query) => $query->where('email', 'like', "%{$search}%"))
            ->latest()
            ->get();

        $filters = collect(request('ranges', ['week']));
        $rangeConfig = [
            'week' => 7,
            'month' => 30,
            'year' => 365,
        ];

        $chartData = [];
        foreach ($filters as $range) {
            if (!isset($rangeConfig[$range])) {
                continue;
            }

            $days = $rangeConfig[$range];
            $fromDate = Carbon::today()->subDays($days - 1);

            $counts = Booking::query()
                ->selectRaw('DATE(date) as day, COUNT(DISTINCT user_id) as clients')
                ->whereDate('date', '>=', $fromDate)
                ->groupBy(DB::raw('DATE(date)'))
                ->orderBy('day')
                ->pluck('clients', 'day');

            $labels = [];
            $series = [];
            for ($cursor = $fromDate->copy(); $cursor->lte(Carbon::today()); $cursor->addDay()) {
                $key = $cursor->toDateString();
                $labels[] = $cursor->format('d.m');
                $series[] = (int) ($counts[$key] ?? 0);
            }

            $chartData[$range] = [
                'label' => $range,
                'labels' => $labels,
                'series' => $series,
            ];
        }

        return view('admin.users', compact('users', 'chartData', 'filters', 'search'));
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'usertype' => 'required|in:user,admin',
        ]);

        $user->update([
            'usertype' => $request->usertype,
        ]);

        return back()->with('success', 'User role updated.');
    }

    public function actionPanel()
    {
        $bookings = Booking::query()
            ->with(['user', 'procedure'])
            ->latest()
            ->take(100)
            ->get();

        $loyaltyLogs = LoyaltyScanLog::query()
            ->with(['user', 'admin'])
            ->latest()
            ->take(100)
            ->get();

        $registrations = User::query()
            ->latest()
            ->take(100)
            ->get();

        return view('admin.action-panel', compact('bookings', 'loyaltyLogs', 'registrations'));
    }
}
