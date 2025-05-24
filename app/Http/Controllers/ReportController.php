<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Post;

class ReportController extends Controller
{
    // Store a new report
    public function store(Request $request, $post_id)
    {
        $request->validate([
            'reporter_name' => 'nullable|string|max:255',
            'reporter_contact' => 'nullable|string|max:255',
            'reason' => 'required|string',
        ]);

        $post = Post::findOrFail($post_id);

        Report::create([
            'post_id' => $post->id,
            'reporter_name' => $request->reporter_name,
            'reporter_contact' => $request->reporter_contact,
            'reason' => $request->reason,
        ]);

        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Report submitted. Thank you for helping us keep the platform safe!');
    }
}