<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyStamp;
use App\Models\LoyaltyScanLog;
use App\Models\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LoyaltyController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $user->ensureLoyaltyCode();
        $stamp = $user->loyaltyStamp ?? LoyaltyStamp::create(['user_id' => $user->id, 'stamps' => 0]);

        return view('layouts.user.loyalty', compact('user', 'stamp'));
    }

    public function adminIndex()
    {
        return view('admin.loyalty', [
            'recentLogs' => LoyaltyScanLog::with('user')->latest()->take(10)->get(),
        ]);
    }

    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'source' => 'nullable|in:code,qr',
        ]);

        $user = User::where('loyalty_code', $request->code)->first();

        if (!$user) {
            return back()->with('error', 'Nepareizs kods!');
        }

        $stamp = $user->loyaltyStamp ?? LoyaltyStamp::create(['user_id' => $user->id]);
        $stamp->addStamp();

        LoyaltyScanLog::create([
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
            'code' => $request->code,
            'source' => $request->input('source', 'code'),
            'action' => 'scan',
        ]);

        return back()->with('success', 'Zīmogs pievienots! Kopā: ' . $stamp->stamps);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $user = User::where('loyalty_code', $request->code)->first();
        if (!$user) {
            return back()->with('error', 'Klients ar šo kodu nav atrasts.');
        }

        $stamp = $user->loyaltyStamp ?? LoyaltyStamp::create(['user_id' => $user->id]);
        $stamp->resetStamps();

        LoyaltyScanLog::create([
            'user_id' => $user->id,
            'admin_id' => auth()->id(),
            'code' => $request->code,
            'source' => 'code',
            'action' => 'refresh',
        ]);

        return back()->with('success', 'Loyalty kartiņa atiestatīta.');
    }
}