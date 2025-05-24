@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h1 class="display-4 mb-3">How It Works</h1>
                <p class="lead" style="color: #000;">A simple guide to help you find your lost items or return found items</p>
            </div>

            <div class="steps-container">
                <!-- Step 1 -->
                <div class="step-card mb-4">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Create Your Post</h3>
                        <p>Start by creating a post about your lost item or an item you've found. Include details like item name, description, and where you lost or found it.</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="step-card mb-4">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Your Post Goes Live</h3>
                        <p>Once submitted, your post will appear on our homepage, making it visible to everyone in the CSU community.</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="step-card mb-4">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Wait for Contact</h3>
                        <p>If you've lost an item, wait for someone who might have found it to contact you through the provided contact information.</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="step-card mb-4">
                    <div class="step-number">4</div>
                    <div class="step-content">
                        <h3>Browse Found Items</h3>
                        <p>If you're looking for a lost item, browse through the found items section to see if someone has already posted about finding your item.</p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="step-card mb-4">
                    <div class="step-number">5</div>
                    <div class="step-content">
                        <h3>For Campus Insiders</h3>
                        <p>If you're a CSU student or staff member and have found an item, please turn it over to the University Student Government (USG) office.</p>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="step-card mb-4">
                    <div class="step-number">6</div>
                    <div class="step-content">
                        <h3>For Campus Visitors</h3>
                        <p>If you're a visitor who found an item, wait for the owner to contact you. Once the item is returned, contact our admin to confirm the return.</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('posts.create') }}" class="btn btn-csu btn-lg">
                    <i class="fas fa-plus-circle me-2"></i>Create a Post Now
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.step-card {
    display: flex;
    align-items: flex-start;
    background: #fff;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s ease;
}

.step-card:hover {
    transform: translateY(-2px);
}

.step-number {
    background: #006400;
    color: #FFD700;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    font-weight: bold;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.step-content {
    flex-grow: 1;
}

.step-content h3 {
    color: #006400;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.step-content p {
    color: #666;
    margin-bottom: 0;
    line-height: 1.6;
}

.btn-csu {
    background-color: #006400;
    border-color: #006400;
    color: #FFD700;
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.btn-csu:hover {
    background-color: #004d00;
    border-color: #004d00;
    color: #FFD700;
    transform: translateY(-2px);
}
</style>
@endsection 