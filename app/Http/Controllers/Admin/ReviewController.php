<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'house'])->latest();

        if ($request->filled('status')) {
            if ($request->status == 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status == 'approved') {
                $query->where('is_approved', true);
            }
        }

        if ($request->filled('user_search')) {
            $searchTerm = $request->user_search;
            $query->whereHas('user', function ($q) use ($searchTerm) {
                $q->where('full_name', 'like', "%{$searchTerm}%")
                  ->orWhere('user_name', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('house_search')) {
            $searchTerm = $request->house_search;
            $query->whereHas('house', function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%");
            });
        }

        $reviews = $query->paginate(15)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Approve the specified review.
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return redirect()->route('admin.reviews.index')->with('success', 'Review approved successfully.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted successfully.');
    }
}
