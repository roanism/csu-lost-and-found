<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Post;
use App\Models\Report;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('admin/dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    // Admin dashboard
    public function dashboard(Request $request)
    {
        $query = Post::query();

        // Handle search
        if ($request->filled('search')) {
            $query->where('reference_number', 'like', '%' . $request->search . '%');
        }

        // Enhanced date-based filtering
        if ($request->filled('date_filter')) {
            $query->whereDate('created_at', $request->date_filter);
        } else {
            if ($request->filled('year')) {
                $query->whereYear('created_at', $request->year);
            }
            if ($request->filled('month')) {
                $query->whereMonth('created_at', $request->month);
            }
        }

        // Handle status filter
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'open':
                    $query->where('status', 'open');
                    break;
                case 'pending':
                    $query->whereIn('status', ['pending_claim', 'pending_return']);
                    break;
                case 'resolved':
                    $query->whereIn('status', ['claimed', 'returned']);
                    break;
            }
        }

        // Handle sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Get statistics
        $totalPosts = Post::count();
        $activePosts = Post::where('status', 'open')->count();
        $pendingClaims = Post::whereIn('status', ['pending_claim', 'pending_return'])->count();
        $resolvedPosts = Post::whereIn('status', ['claimed', 'returned'])->count();
        $unreadMessages = Contact::where('is_read', false)->count();
        $pendingReports = Report::where('resolved', false)->count();

        // Get filtered posts
        $posts = $query->paginate(10)->withQueryString();

        return view('admin.dashboard', compact(
            'posts',
            'totalPosts',
            'activePosts',
            'pendingClaims',
            'resolvedPosts',
            'unreadMessages',
            'pendingReports'
        ));
    }

    // Show post details
    public function showPost($id)
    {
        $post = Post::with(['reports', 'claims'])->findOrFail($id);
        return view('admin.posts.show', compact('post'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }

    // Delete a post
    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Post deleted successfully.');
    }

    // Approve a claim
    public function approveClaim($id)
    {
        $post = Post::findOrFail($id);
        
        // Only allow approval if the status is pending
        if (!in_array($post->status, ['pending_claim', 'pending_return'])) {
            return redirect()->back()
                ->with('error', 'This item is not in a pending state for approval.');
        }
        
        // Set the final status based on the post type and current status
        if ($post->status === 'pending_return') {
            $post->status = 'returned';
        } else if ($post->status === 'pending_claim') {
            $post->status = 'claimed';
        }
        
        $post->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Claim has been approved successfully.');
    }

    public function messages()
    {
        $query = Contact::query();
        
        // Handle search
        if (request()->filled('search')) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . request('search') . '%')
                  ->orWhere('email', 'like', '%' . request('search') . '%')
                  ->orWhere('subject', 'like', '%' . request('search') . '%');
            });
        }

        // Handle status filter
        if (request()->filled('status')) {
            switch (request('status')) {
                case 'unread':
                    $query->where('is_read', false);
                    break;
                case 'read':
                    $query->where('is_read', true);
                    break;
            }
        }
        
        // Enhanced date-based filtering
        if (request()->filled('date_filter')) {
            $query->whereDate('created_at', request('date_filter'));
        } else {
            if (request()->filled('year')) {
                $query->whereYear('created_at', request('year'));
            }
            if (request()->filled('month')) {
                $query->whereMonth('created_at', request('month'));
            }
        }
        
        // Handle sorting
        $sort = request('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        $messages = $query->paginate(10)->withQueryString();
        return view('admin.messages', compact('messages'));
    }

    public function markMessageAsRead($id)
    {
        $message = Contact::findOrFail($id);
        $message->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Message marked as read.');
    }

    public function showMessage($id)
    {
        $message = Contact::findOrFail($id);
        return view('admin.message-show', compact('message'));
    }

    // Show post edit form
    public function editPost($id)
    {
        $post = Post::findOrFail($id);
        return view('admin.posts.edit', compact('post'));
    }

    // Update a post
    public function updatePost(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'type' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'contact_name' => 'required|string|max:255',
            'contact_info' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:open,pending_claim,pending_return,claimed,returned'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $imagePath = $request->file('image')->store('uploads', 'public');
            $post->image_path = $imagePath;
        }

        $post->update([
            'type' => $request->type,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'category' => $request->category,
            'location' => $request->location,
            'contact_name' => $request->contact_name,
            'contact_info' => $request->contact_info,
            'status' => $request->status
        ]);

        return redirect()->route('admin.posts.show', $post->id)
            ->with('success', 'Post updated successfully.');
    }

    // Show reports page
    public function reports()
    {
        $query = Report::with(['post']);
        
        // Handle date filter
        if (request()->filled('date_filter')) {
            $query->whereDate('created_at', request('date_filter'));
        }
        
        // Handle sorting
        $sort = request('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $reports = $query->paginate(10)->withQueryString();
        return view('admin.reports', compact('reports'));
    }

    // Resolve a report
    public function resolveReport($id)
    {
        $report = Report::findOrFail($id);
        $report->update(['resolved' => true]);

        return redirect()->route('admin.reports')
            ->with('success', 'Report has been marked as resolved.');
    }

    // Delete a message
    public function deleteMessage($id)
    {
        $message = Contact::findOrFail($id);
        $message->delete();

        return redirect()->route('admin.messages')
            ->with('success', 'Message deleted successfully.');
    }

    // Delete a report
    public function deleteReport($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();

        return redirect()->route('admin.reports')
            ->with('success', 'Report deleted successfully.');
    }

    public function bulkDelete(Request $request)
    {
        $postIds = json_decode($request->input('post_ids'), true);
        
        if (!$postIds || !is_array($postIds)) {
            return redirect()->back()->with('error', 'No posts selected for deletion.');
        }

        try {
            Post::whereIn('id', $postIds)->delete();
            return redirect()->back()->with('success', count($postIds) . ' post(s) have been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the posts.');
        }
    }

    public function bulkDeleteMessages(Request $request)
    {
        $messageIds = json_decode($request->input('message_ids'), true);
        
        if (!$messageIds || !is_array($messageIds)) {
            return redirect()->back()->with('error', 'No messages selected for deletion.');
        }

        try {
            Contact::whereIn('id', $messageIds)->delete();
            return redirect()->back()->with('success', count($messageIds) . ' message(s) have been deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the messages.');
        }
    }

    public function bulkDeleteReports(Request $request)
    {
        try {
            $reportIds = json_decode($request->report_ids);
            
            if (empty($reportIds)) {
                return back()->with('error', 'No reports selected for deletion.');
            }
            
            Report::whereIn('id', $reportIds)->delete();
            
            return back()->with('success', 'Selected reports have been deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while deleting the reports.');
        }
    }
}