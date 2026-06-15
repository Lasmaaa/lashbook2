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
        $procedures = Procedure::whereNotIn('code', ['volume_2d_3d', 'volume_4d_plus'])->get();
        $volumeOptions = Procedure::whereIn('code', ['volume_2d_3d', 'volume_4d_plus'])->get();

        return view('layouts.user.calendar', compact('procedures', 'volumeOptions'));
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
            'volume_option' => 'nullable|exists:procedures,id',
            'details' => 'nullable|string',
        ]);

        $procedureIds = collect($request->procedure_id)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $volumeProcedure = Procedure::where('code', 'volume')->first();
        $volumeOptionIds = Procedure::whereIn('code', ['volume_2d_3d', 'volume_4d_plus'])->pluck('id')->toArray();

        if ($volumeProcedure && $procedureIds->contains($volumeProcedure->id)) {
            $volumeOptionId = (int) $request->input('volume_option');
            if (!$volumeOptionId || !in_array($volumeOptionId, $volumeOptionIds, true)) {
                return back()
                    ->withErrors(['volume_option' => 'Lūdzu izvēlies apjoma veidu.'])
                    ->withInput();
            }

            $procedureIds = $procedureIds->reject(fn ($id) => $id === $volumeProcedure->id);
            $procedureIds->push($volumeOptionId);
        }

        $procedureIds = $procedureIds->unique()->values()->all();

        $selectedProcedures = Procedure::query()
            ->whereIn('id', $procedureIds)
            ->get();

        if ($selectedProcedures->contains('code', 'classic')
            && $selectedProcedures->contains(fn ($procedure) => in_array($procedure->code, ['volume', 'volume_2d_3d', 'volume_4d_plus'], true))) {
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

        foreach ($procedureIds as $id) {
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