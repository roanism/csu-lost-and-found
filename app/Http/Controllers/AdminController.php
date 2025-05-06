<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

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
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Session::put('admin_id', $admin->id);
            return redirect()->route('admin.dashboard');
        } else {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }
    }

    // Admin dashboard
    public function dashboard()
    {
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login');
        }
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);
        $reports = Report::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.dashboard', compact('posts', 'reports'));
    }

    // Logout
    public function logout()
    {
        Session::forget('admin_id');
        return redirect()->route('admin.login');
    }

    // Delete a post
    public function deletePost($id)
    {
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login');
        }
        $post = Post::findOrFail($id);
        $post->delete();
        return back()->with('success', 'Post deleted successfully.');
    }

    // Update post status (open, claimed, returned)
    public function updateStatus(Request $request, $id)
    {
        if (!Session::has('admin_id')) {
            return redirect()->route('admin.login');
        }
        $post = Post::findOrFail($id);
        $post->status = $request->status;
        $post->save();
        return back()->with('success', 'Post status updated.');
    }
}