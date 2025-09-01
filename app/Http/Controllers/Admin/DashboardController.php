<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AboutSlide;
use App\Models\Testimonial;
use App\Models\Gallery;
use App\Models\Venue;
use App\Models\Blog;
use App\Models\ContactSubmission;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'about_slides' => AboutSlide::count(),
            'testimonials' => Testimonial::count(),
            'galleries' => Gallery::count(),
            'venues' => Venue::count(),
            'blogs' => Blog::count(),
            'media_files' => Media::count(),
            'contact_submissions' => ContactSubmission::count(),
            'unread_contacts' => ContactSubmission::unread()->count(),
            'published_blogs' => Blog::published()->count(),
            'active_venues' => Venue::active()->count(),
        ];

        // Recent activity
        $recent_contacts = ContactSubmission::latest()->take(5)->get();
        $recent_blogs = Blog::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recent_contacts', 'recent_blogs'));
    }
}
