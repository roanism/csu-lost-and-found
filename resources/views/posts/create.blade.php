@extends('layouts.app')

@section('content')
<a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">Back</a>
<h2 class="csu-header mb-4">Post Lost or Found Item</h2>
<form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label>Type</label>
        <select name="type" class="form-control" required>
            <option value="lost">Lost</option>
            <option value="found">Found</option>
        </select>
    </div>
    <div class="form-group">
        <label>Item Name</label>
        <input type="text" name="item_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3" required></textarea>
    </div>
    <div class="form-group">
        <label>Category (optional)</label>
        <input type="text" name="category" class="form-control">
    </div>
    <div class="form-group">
        <label>Location (optional)</label>
        <input type="text" name="location" class="form-control">
    </div>
    <div class="form-group">
        <label>Contact Name</label>
        <input type="text" name="contact_name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Contact Info</label>
        <input type="text" name="contact_info" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Image (optional)</label>
        <input type="file" name="image" class="form-control-file">
    </div>
    <button type="submit" class="btn btn-csu">Submit</button>
</form>
@endsection