<?php

namespace App\Http\Controllers\Api;

use App\Traits\GeneralTrait;
use App\Http\Controllers\Controller;

use App\Models\{Category};
use App\Http\Resources\{CityResource, StateResource, CategoryResource};

class CategoryApi extends Controller
{
    use GeneralTrait;
    public function get_all_categories()
    {
        $categories = CategoryResource::collection(Category::where('parent_id', null)->latest()->get());
        return $this->returnData("data", $categories, "All categories");
    }

    public function get_sub_categories($category_id)
    {
        if (!$category_id) {
            return $this->returnError(false, 404, "Category id is required");
        }
        $categories = CategoryResource::collection(Category::where("parent_id", $category_id)->latest()->get());
        return $this->returnData("data", $categories, "Sub Categories");
    }

}
