<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PanelToken;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(PanelToken $panelToken)
    {
        $categories = Category::with('currency')->cursor();
        return response()->json([
            'data' => CategoryResource::collection($categories)
        ]);
    }
}
