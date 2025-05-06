<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\Post;

class ClaimController extends Controller
{
    // Store a new claim (mark as claimed/returned)
    public function store(Request $request, $post_id)
    {
        $request->validate([
            'claimer_name' => 'required|string|max:255',
            'claimer_contact' => 'required|string|max:255',
            'claim_type' => 'required|in:claimed,returned',
            'notes' => 'nullable|string',
        ]);

        $post = Post::findOrFail($post_id);

        // Create claim record
        Claim::create([
            'post_id' => $post->id,
            'claimer_name' => $request->claimer_name,
            'claimer_contact' => $request->claimer_contact,
            'claim_type' => $request->claim_type,
            'notes' => $request->notes,
        ]);

        // Update post status
        $post->status = $request->claim_type;
        $post->save();

        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Item marked as ' . $request->claim_type . '!');
    }
}