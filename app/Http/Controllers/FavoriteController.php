<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use App\Models\Favorite;
use App\Models\Business;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'entity_type' => ['required', 'string', Rule::in([
                Attraction::class,
                Business::class,
                Event::class,
            ])],
            'entity_id'   => 'required|integer'
        ]);

        $user = auth()->user();

        $favorite = Favorite::where([
            'user_id'     => $user->id,
            'entity_type' => $request->entity_type,
            'entity_id'   => $request->entity_id,
        ])->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        }

        $entityClass = $request->entity_type;
        $entity = $entityClass::findOrFail($request->entity_id);

        Favorite::create([
            'user_id'     => $user->id,
            'entity_type' => $request->entity_type,
            'entity_id'   => $request->entity_id,
            'created_at'  => now(),
        ]);

        return response()->json(['status' => 'added']);
    }
}
