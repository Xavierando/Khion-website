<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;

class HomePageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $teams = UserResource::collection(User::where('isTeam', '=', '1')->get());
        $products = ProductResource::collection(Product::orderBy('created_at', 'desc')->get());
        $fproducts = $products->filter(function ($v) {
            return $v->available_quantity > 0;
        })->shift(4);

        return Inertia::render(
            'Welcome',
            [
                'teams' => $teams,
                'products' => $fproducts,
            ]
        );
    }
}
