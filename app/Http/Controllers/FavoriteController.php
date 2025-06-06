<?php

namespace App\Http\Controllers;

use App\Models\House;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle the favorite status of a house for the authenticated user.
     *
     * @param House $house
     * @return JsonResponse
     */
    public function showFavorites()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            // This should ideally be handled by 'auth' middleware.
            // Redirect to login if somehow an unauthenticated user reaches here.
            return redirect()->route('login')->with('error', 'You must be logged in to view your favorites.');
        }

        // Retrieve the user's favorited houses, eager-loading the pictures
        // and ordering by when they were favorited (newest first).
        $favoriteHouses = $user->favorites()
            ->with('pictures') // Eager load pictures to prevent N+1 queries
            ->latest('favorites.created_at') // Order by the pivot table's created_at timestamp
            ->get();

        return view('Favorites.favorites', compact('favoriteHouses'));
    }
    public function toggleFavorite(House $house): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            // This case should ideally be prevented by auth middleware,
            // but as a fallback or for API usage where session might not be primary.
            return response()->json(['status' => 'error', 'message' => 'Unauthenticated.'], 401);
        }

        if ($user->hasFavorited($house)) {
            $user->favorites()->detach($house->id);
            return response()->json(['status' => 'removed', 'message' => 'House removed from favorites.']);
        } else {
            $user->favorites()->attach($house->id);
            return response()->json(['status' => 'added', 'message' => 'House added to favorites.']);
        }
    }
}
