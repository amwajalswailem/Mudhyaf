<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RecommendationService;
use Illuminate\View\View;

class AdminRecommendationController extends Controller
{
    public function index(RecommendationService $service): View
    {
        $metrics = $service->metrics();

        return view('admin.recommendations.index', compact('metrics'));
    }
}
