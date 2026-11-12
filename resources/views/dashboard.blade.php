@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-card" style="padding: 20px;">
    <h2 class="card-title" style="font-size: 1.15rem; margin-bottom: 8px;">Welcome!</h2>
    <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">You have successfully logged into the OSEDU Bright Future Portal system.</p>
    <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">You can manage your courses, view your progress, and access various features based on your role. Use the sidebar to navigate through different sections of the portal.</p>

    @role('Admin')
        <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">As an admin, you have full access to manage users, courses, and other administrative tasks.</p>
        <div class="quick-stats">
        </div>
    @endrole

    @role('Tutor')
        <p class="card-desc" style="margin-bottom: 20px; color: #94a3b8;">As a tutor, you can create and manage your courses, quizzes, and track student progress.</p>
        <div class="quick-stats">
        </div>
    @endrole
</div>
@endsection