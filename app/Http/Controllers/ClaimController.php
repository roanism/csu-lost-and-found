<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class ClaimController extends Controller
{
    // Store a new claim (mark as claimed/returned)
    public function store(Request $request, $post_id)
    {
        $request->validate([
            'claimer_name' => 'required|string|max:255',
            'claimer_email' => 'required|email|max:255',
            'claimer_phone' => 'required|string|max:20',
            'claim_reason' => 'required|string',
        ]);

        $post = Post::findOrFail($post_id);

        // Create the claim record
        $claim = new Claim([
            'claimer_name' => $request->claimer_name,
            'claimer_email' => $request->claimer_email,
            'claimer_phone' => $request->claimer_phone,
            'claim_reason' => $request->claim_reason,
        ]);

        $post->claims()->save($claim);

        // Set a temporary status based on post type
        $status = $post->type === 'lost' ? 'pending_return' : 'pending_claim';
        
        // Enable query logging
        DB::enableQueryLog();
        
        // Update using DB facade with explicit value binding
        DB::table('posts')
            ->where('id', $post->id)
            ->update([
                'status' => DB::raw("'$status'"),
                'updated_at' => now()
            ]);
            
        // Log the query
        \Log::info(DB::getQueryLog());

        return redirect()->route('posts.show', $post_id)
            ->with('success', 'Your claim has been submitted successfully. The admin will review your claim.');
    }
}