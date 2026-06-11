<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Procedure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        $nextBooking = Booking::with('procedure')
            ->where('user_id', auth()->id())
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->first();

        return view('layouts.user.index', compact('nextBooking'));
    }

    public function calendar()
    {
        $procedures = Procedure::all();
        return view('layouts.user.calendar', compact('procedures'));
    }

    public function availableTimes(Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $allSlots = collect([
            '09:00', '10:00', '11:00', '12:00', '13:00',
            '14:00', '15:00', '16:00', '17:00', '18:00',
        ]);

        $available = $allSlots->filter(function (string $slot) use ($request) {
            return !Booking::query()
                ->whereDate('date', $request->date)
                ->whereTime('time', $slot)
                ->exists();
        })->values();

        $takenSlots = $allSlots->diff($available)->values();

        return response()->json([
            'available_times' => $available,
            'taken_times' => $takenSlots,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after:yesterday',
            'time' => 'required',
            'procedure_id' => 'required|array|min:1',
            'procedure_id.*' => 'required|exists:procedures,id',
            'details' => 'nullable|string',
        ]);

        $selectedProcedures = Procedure::query()
            ->whereIn('id', $request->procedure_id)
            ->get();

        $selectedNames = $selectedProcedures->pluck('name_lv')->map(fn ($name) => mb_strtolower($name));
        if (
            ($selectedNames->contains('apjoms') || $selectedNames->contains('apjoma pieaudzējums'))
            && ($selectedNames->contains('klasika') || $selectedNames->contains('klasiskais pieaudzējums'))
        ) {
            return back()
                ->withErrors(['procedure_id' => 'Apjomu un klasiku reizē izvēlēties nevar.'])
                ->withInput();
        }

        $isTaken = Booking::query()
            ->whereDate('date', $request->date)
            ->whereTime('time', $request->time)
            ->exists();

        if ($isTaken) {
            return back()
                ->withErrors(['time' => 'Izvēlētais laiks vairs nav pieejams.'])
                ->withInput();
        }

        foreach ($request->procedure_id as $id) {
            Booking::create([
                'user_id' => auth()->id(),
                'procedure_id' => $id,
                'date' => $request->date,
                'time' => $request->time,
                'details' => $request->details,
            ]);
        }

        return redirect()->route('user.index')
            ->with('success', 'Pieraksts ir veiksmīgi izveidots.');
    }
}