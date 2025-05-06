<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // Show all posts (with optional filtering)
    public function index(Request $request)
    {
        $query = Post::query();
    
        // Filtering by type, category
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        $posts = $query->orderBy('created_at', 'desc')->paginate(10);
    
        return view('posts.index', compact('posts'));
    }

    // Show a single post
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    // Show the form to create a new post
    public function create()
    {
        return view('posts.create');
    }

    // Store a new post
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads', 'public');
        }

        // Generate a unique reference number
        $referenceNumber = strtoupper(Str::random(8));

        $post = Post::create([
            'type' => $request->type,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'contact_name' => $request->contact_name,
            'contact_info' => $request->contact_info,
            'image_path' => $imagePath,
            'reference_number' => $referenceNumber,
            'status' => 'open',
        ]);

        return redirect()->route('posts.show', $post->id)
            ->with('success', 'Post created successfully! Your reference number is: ' . $referenceNumber);
    }
}