<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category as CategoryModel;

class Category extends Component
{
    public $subcategories, $attributes = [], $category_id, $subcategory_id, $categories, $catAttributes, $key2;
    public function __construct($category_id = null, $catAttributes = null) {
        $this->catAttributes = $catAttributes;
        if ($category_id) {
            $this->category_id = $category_id;
            // $this->subCategory();
            // $this->subcategories = CategoryModel::where('parent_id', $category_id)->get();
        }
        return $this->emit('update');
    }
    public function booted()
    {
        // dd($this->category_id);
        $this->subcategories = CategoryModel::where('parent_id', $this->category_id)->get();
    }
    public function subCategory() {

        $this->subcategories = CategoryModel::where('parent_id', $this->category_id)->get();
    }

    public function att() {
        $category = CategoryModel::where('id', $this->category_id)->first();
        $subcategory = CategoryModel::where('id', $this->subcategory_id)->first();

        if ($subcategory && $subcategory->attributes->count()) {
            $this->attributes = $subcategory->attributes;
        } else {
            $this->attributes = $category->attributes;
        }
    }
    public function render()
    {
        $this->categories = CategoryModel::where('parent_id', null)->get();
        return view('livewire.category');
    }
}
