<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Procedure;
use App\Models\ScheduleProcedure;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private ScheduleService $schedule)
    {
    }

    public function index()
    {
        $nextBooking = Booking::with(['procedure', 'scheduleProcedure'])
            ->where('user_id', auth()->id())
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->first();

        return view('layouts.user.index', compact('nextBooking'));
    }

    public function calendar()
    {
        return view('layouts.user.calendar');
    }

    public function scheduleForDate(Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $date = $request->date;
        $allSlots = $this->schedule->getTimesForDate($date);

        $available = $allSlots->filter(function (string $slot) use ($date) {
            return !Booking::query()
                ->whereDate('date', $date)
                ->whereTime('time', $slot)
                ->exists();
        })->values();

        $procedures = $this->schedule->getProceduresForDate($date)->map(function ($procedure) {
            $lang = app()->getLocale();

            return [
                'ref' => $procedure->ref,
                'name' => match ($lang) {
                    'en' => $procedure->name_en,
                    'ru' => $procedure->name_ru,
                    default => $procedure->name_lv,
                },
                'price' => number_format((float) $procedure->price, 2),
            ];
        });

        return response()->json([
            'procedures' => $procedures,
            'available_times' => $available,
        ]);
    }

    public function availableTimes(Request $request): JsonResponse
    {
        return $this->scheduleForDate($request);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after:yesterday',
            'time' => 'required',
            'client_name' => 'required|string|max:255',
            'procedure_ref' => 'required|string',
            'details' => 'nullable|string',
        ]);

        $isTaken = Booking::query()
            ->whereDate('date', $request->date)
            ->whereTime('time', $request->time)
            ->exists();

        if ($isTaken) {
            return back()
                ->withErrors(['time' => __('ui.time_taken')])
                ->withInput();
        }

        $procedureRef = $request->procedure_ref;
        $bookingData = [
            'user_id' => auth()->id(),
            'client_name' => $request->client_name,
            'date' => $request->date,
            'time' => $request->time,
            'details' => $request->details,
        ];

        if (str_starts_with($procedureRef, 'sched-')) {
            $scheduleProcedure = ScheduleProcedure::find((int) str_replace('sched-', '', $procedureRef));
            if (!$scheduleProcedure) {
                return back()->withErrors(['procedure_ref' => __('ui.invalid_procedure')])->withInput();
            }
            $bookingData['schedule_procedure_id'] = $scheduleProcedure->id;
            $bookingData['procedure_id'] = Procedure::query()->value('id');
        } elseif (str_starts_with($procedureRef, 'proc-')) {
            $procedure = Procedure::find((int) str_replace('proc-', '', $procedureRef));
            if (!$procedure) {
                return back()->withErrors(['procedure_ref' => __('ui.invalid_procedure')])->withInput();
            }
            $bookingData['procedure_id'] = $procedure->id;
        } else {
            return back()->withErrors(['procedure_ref' => __('ui.invalid_procedure')])->withInput();
        }

        Booking::create($bookingData);

        return redirect()->route('user.index')
            ->with('success', __('ui.booking_success'));
    }
}
