<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Business;
use App\Models\Event;
use App\Models\Review;
use App\Models\Favorite;
use App\Models\BusinessCategory;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Basic totals
        $totalUsers = User::count();
        $totalBusinesses = Business::count();
        $totalEvents = Event::count();
        $totalReviews = Review::count();
        $totalFavorites = Favorite::count();

        // Status-based
        $pendingBusinesses = Business::where('status', 'pending')->count();
        $approvedBusinesses = Business::where('status', 'approved')->count();
        $pendingEvents = Event::where('status', 'pending')->count();
        $approvedEvents = Event::where('status', 'approved')->count();

        // Roles breakdown
        $usersByRole = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->pluck('total', 'role')
            ->toArray();

        // Recent items
        $latestUsers = User::orderByDesc('id')->limit(5)->get(['id', 'full_name', 'email', 'role', 'created_at']);
        $latestBusinesses = Business::with(['owner', 'category'])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        $latestEvents = Event::with(['creator'])
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        // Last 7 days activity (based on created_at existing in your schema)
        $from = Carbon::now()->subDays(6)->startOfDay();

        $usersLast7Days = User::where('created_at', '>=', $from)->count();
        $businessesLast7Days = Business::where('created_at', '>=', $from)->count();
        $eventsLast7Days = Event::where('created_at', '>=', $from)->count();
        $reviewsLast7Days = Review::where('created_at', '>=', $from)->count();

        // Top categories by businesses count
        $topCategories = BusinessCategory::withCount('businesses')
            ->orderByDesc('businesses_count')
            ->limit(6)
            ->get();

        // Avg rating
        $avgRating = Review::avg('rating');
        $avgRating = $avgRating ? round($avgRating, 1) : 0;

        $stats = compact(
            'totalUsers',
            'totalBusinesses',
            'totalEvents',
            'totalReviews',
            'totalFavorites',
            'pendingBusinesses',
            'approvedBusinesses',
            'pendingEvents',
            'approvedEvents',
            'usersByRole',
            'latestUsers',
            'latestBusinesses',
            'latestEvents',
            'usersLast7Days',
            'businessesLast7Days',
            'eventsLast7Days',
            'reviewsLast7Days',
            'topCategories',
            'avgRating'
        );

        return view('admin.dashboard', $stats);
    }
}
