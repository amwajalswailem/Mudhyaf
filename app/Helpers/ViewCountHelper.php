<?php

namespace App\Helpers;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class ViewCountHelper
{
    /**
     * Track video view and check if access is allowed
     */
    public static function trackVideoView($unitId)
    {
        if (!Auth::check() || Auth::user()->Role !== 'student') {
            return false;
        }

        $studentId = Auth::user()->student->Student_ID;

        $payment = Payment::where('Unit_ID', $unitId)
            ->where('Student_ID', $studentId)
            ->first();

        if (!$payment) {
            return false;
        }

        // Check if views are exhausted
        if ($payment->isViewsExhausted()) {
            return false;
        }

        // Increment view count
        return $payment->incrementViewCount();
    }

    /**
     * Check if student can access the unit video
     */
    public static function canAccessVideo($unitId)
    {
        if (!Auth::check() || Auth::user()->Role !== 'student') {
            return false;
        }

        $studentId = Auth::user()->student->Student_ID;

        $payment = Payment::where('Unit_ID', $unitId)
            ->where('Student_ID', $studentId)
            ->first();

        return $payment && $payment->canAccessUnit();
    }

    /**
     * Get view count information
     */
    public static function getViewInfo($unitId)
    {
        if (!Auth::check() || Auth::user()->Role !== 'student') {
            return null;
        }

        $studentId = Auth::user()->student->Student_ID;

        $payment = Payment::where('Unit_ID', $unitId)
            ->where('Student_ID', $studentId)
            ->first();

        if (!$payment) {
            return null;
        }

        return [
            'current_views' => $payment->View_count,
            'remaining_views' => $payment->getRemainingViews(),
            'is_exhausted' => $payment->isViewsExhausted(),
            'payment' => $payment
        ];
    }
}
