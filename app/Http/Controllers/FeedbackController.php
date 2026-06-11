<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function create()
    {
        return view('layouts.user.feedback-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'photo' => 'nullable|image|max:4096',
        ]);

        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('feedback', 'public')
            : null;

        Feedback::create([
            'user_id' => auth()->id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'photo' => $photoPath,
        ]);

        return redirect()->route('user.index')->with('success', 'Paldies par atsauksmi!');
    }
}
