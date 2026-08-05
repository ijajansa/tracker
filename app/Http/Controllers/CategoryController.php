<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::query()->active()->orderBy('type')->orderBy('name')->get();
    }
}
