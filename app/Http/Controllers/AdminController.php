<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Feedback;
use App\Models\LoyaltyScanLog;
use App\Models\ScheduleProcedure;
use App\Models\ScheduleTime;
use App\Models\User;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct(private ScheduleService $schedule)
    {
    }

    public function index()
    {
        return redirect()->route('admin.action-panel');
    }

    public function bookings()
    {
        $bookingsByDate = Booking::selectRaw('date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        return view('admin.bookings', compact('bookingsByDate'));
    }

    public function bookingsByDate($date)
    {
        $bookings = Booking::with(['user', 'procedure', 'scheduleProcedure'])
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

        return back()->with('success', __('ui.status_updated'));
    }

    public function procedures()
    {
        $datesWithSchedule = ScheduleProcedure::selectRaw('date')
            ->union(ScheduleTime::selectRaw('date'))
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->unique()
            ->flip();

        return view('admin.procedures', compact('datesWithSchedule'));
    }

    public function proceduresForDate(string $date)
    {
        $procedures = $this->schedule->getProceduresForDate($date)->map(fn ($item) => [
            'name_lv' => $item->name_lv,
            'name_en' => $item->name_en,
            'name_ru' => $item->name_ru,
            'price' => (float) $item->price,
        ])->values();

        $times = $this->schedule->getTimesForDate($date)->values();

        return response()->json([
            'date' => $date,
            'procedures' => $procedures,
            'times' => $times,
            'has_custom' => $this->schedule->hasCustomSchedule($date),
        ]);
    }

    public function saveProceduresForDate(Request $request, string $date)
    {
        $validated = $request->validate([
            'procedures' => 'nullable|array',
            'procedures.*.name_lv' => 'nullable|string|max:255',
            'procedures.*.name_en' => 'nullable|string|max:255',
            'procedures.*.name_ru' => 'nullable|string|max:255',
            'procedures.*.price' => 'nullable|numeric|min:0',
            'times' => 'nullable|array',
            'times.*' => 'nullable|string',
        ]);

        $procedures = collect($validated['procedures'] ?? [])
            ->filter(function (array $procedure) {
                return trim((string) ($procedure['name_lv'] ?? '')) !== ''
                    || trim((string) ($procedure['name_en'] ?? '')) !== ''
                    || trim((string) ($procedure['name_ru'] ?? '')) !== '';
            })
            ->values()
            ->all();

        $this->schedule->saveForDate(
            $date,
            $procedures,
            $validated['times'] ?? []
        );

        return back()->with('success', __('ui.schedule_saved'));
    }

    public function applyProceduresToAll(Request $request, string $date)
    {
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'procedures' => 'nullable|array',
            'procedures.*.name_lv' => 'nullable|string|max:255',
            'procedures.*.name_en' => 'nullable|string|max:255',
            'procedures.*.name_ru' => 'nullable|string|max:255',
            'procedures.*.price' => 'nullable|numeric|min:0',
            'times' => 'nullable|array',
            'times.*' => 'nullable|string',
        ]);

        if (!empty($validated['procedures']) || !empty($validated['times'])) {
            $procedures = collect($validated['procedures'] ?? [])
                ->filter(function (array $procedure) {
                    return trim((string) ($procedure['name_lv'] ?? '')) !== ''
                        || trim((string) ($procedure['name_en'] ?? '')) !== ''
                        || trim((string) ($procedure['name_ru'] ?? '')) !== '';
                })
                ->values()
                ->all();

            $this->schedule->saveForDate(
                $date,
                $procedures,
                $validated['times'] ?? []
            );
        }

        $count = $this->schedule->applyToAllDates(
            $date,
            Carbon::parse($validated['from_date']),
            Carbon::parse($validated['to_date'])
        );

        return back()->with('success', __('ui.schedule_applied_all', ['count' => $count]));
    }

    public function users()
    {
        $search = request('email');
        $users = User::query()
            ->when($search, fn ($query) => $query->where('email', 'like', "%{$search}%"))
            ->latest()
            ->get();

        return view('admin.users', compact('users', 'search'));
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'usertype' => 'required|in:user,admin',
        ]);

        $user->update([
            'usertype' => $request->usertype,
        ]);

        return back()->with('success', __('ui.role_updated'));
    }

    public function actionPanel()
    {
        $category = request('category', 'all');
        $sort = request('sort', 'newest');

        $events = collect();

        if (in_array($category, ['all', 'registration'], true)) {
            User::query()->latest()->take(200)->get()->each(function (User $user) use ($events) {
                $events->push([
                    'type' => 'registration',
                    'at' => $user->created_at,
                    'label' => __('ui.event_registration', [
                        'name' => $user->fullName(),
                        'email' => $user->email,
                    ]),
                ]);
            });
        }

        if (in_array($category, ['all', 'booking'], true)) {
            Booking::query()->with(['user', 'procedure', 'scheduleProcedure'])->latest()->take(200)->get()->each(function (Booking $booking) use ($events) {
                $events->push([
                    'type' => 'booking',
                    'at' => $booking->created_at,
                    'label' => __('ui.event_booking', [
                        'name' => $booking->client_name ?: $booking->user?->fullName(),
                        'date' => $booking->date?->format('d.m.Y'),
                        'time' => substr((string) $booking->time, 0, 5),
                        'procedure' => $booking->getProcedureName(),
                    ]),
                ]);
            });
        }

        if (in_array($category, ['all', 'loyalty'], true)) {
            LoyaltyScanLog::query()->with(['user', 'admin'])->latest()->take(200)->get()->each(function (LoyaltyScanLog $log) use ($events) {
                $events->push([
                    'type' => 'loyalty',
                    'at' => $log->created_at,
                    'label' => __('ui.event_loyalty', [
                        'name' => $log->user?->fullName(),
                        'code' => $log->code,
                        'action' => strtoupper($log->action),
                    ]),
                ]);
            });
        }

        if (in_array($category, ['all', 'feedback'], true)) {
            Feedback::query()->with('user')->latest()->take(200)->get()->each(function (Feedback $feedback) use ($events) {
                $events->push([
                    'type' => 'feedback',
                    'at' => $feedback->created_at,
                    'label' => __('ui.event_feedback', [
                        'name' => $feedback->user?->fullName(),
                        'rating' => $feedback->rating,
                    ]),
                ]);
            });
        }

        $events = $events->sortBy('at', SORT_REGULAR, $sort === 'newest');

        return view('admin.action-panel', [
            'events' => $events,
            'category' => $category,
            'sort' => $sort,
        ]);
    }
}
