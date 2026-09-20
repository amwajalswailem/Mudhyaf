<?php

namespace App\Http\Controllers;

use App\Models\BusinessCategory;
use App\Services\RecommendationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RecommendationController extends Controller
{
    public function index(Request $request, RecommendationService $service): View
    {
        $scope = $request->input('type');
        $allowedScopes = ['attractions', 'businesses', 'events'];

        if ($scope && ! in_array($scope, $allowedScopes, true)) {
            $scope = null;
        }

        $recommendations = $service->recommendFor($request->user(), $scope, 9);
        $service->recordRecommendationBatch($request->user(), $recommendations);

        $preferences = $service->preferencesFor($request->user());
        $categories = BusinessCategory::orderBy('name')->get();

        return view('recommendations.index', compact('recommendations', 'preferences', 'categories', 'scope'));
    }

    public function updatePreferences(Request $request, RecommendationService $service): RedirectResponse
    {
        $validated = $request->validate([
            'preferred_price_level' => ['nullable', 'in:1,2,3'],
            'min_budget' => ['nullable', 'numeric', 'min:0'],
            'max_budget' => ['nullable', 'numeric', 'min:0'],
            'preferred_categories' => ['nullable', 'array'],
            'preferred_categories.*' => ['integer'],
            'preferred_tags' => ['nullable', 'array'],
            'preferred_tags.*' => ['in:Attraction,Business,Event'],
            'preferred_visit_time' => ['nullable', 'in:morning,afternoon,evening,night'],
        ]);

        if (($validated['min_budget'] ?? null) !== null && ($validated['max_budget'] ?? null) !== null && $validated['min_budget'] > $validated['max_budget']) {
            return back()
                ->withErrors(['max_budget' => 'The maximum budget must be greater than or equal to the minimum budget.'])
                ->withInput();
        }

        $service->savePreferences($request->user(), $validated);

        return back()->with('success', 'Your recommendation preferences were saved.');
    }
}
