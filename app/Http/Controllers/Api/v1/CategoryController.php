<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\PanelToken;
use App\Http\Resources\v1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Enums\CategoryEnum;

class CategoryController extends Controller
{
    public function index(PanelToken $panelToken)
    {
        $categories = Category::with('currency')->when($panelToken->filled('type'),function ($q) use ($panelToken){
            return $q->where('type',$panelToken->get('type'));
        })->cursor();
        return response()->json([
            'data' => CategoryResource::collection($categories)
        ]);
    }

    public function getBase()
    {
        return response()->json([
            'data' => CategoryEnum::getType()
        ]);
    }
}
