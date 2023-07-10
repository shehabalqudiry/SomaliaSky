<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use App\Models\Table;
class Home extends Component
{
    public $categories,
           $subCatrgories,
           $products = [],
           $cartProducts = [],
        //    $qt,
           $c_table,
           $show_subCatrgories = 0,
           $show_Catrgories = 1,
           $show_products = 0,
           $step = 1;

    public function __construct()
    {
        return $this->emit('subCat');
    }

    public function addTocart($product)
    {
        // dd(array_search($product['id'],$this->cartProducts));
        if (array_key_exists($product['id'], $this->cartProducts)) {
            $this->cartProducts[$product['id']]['qt'] = $this->cartProducts[$product['id']]['qt'] + 1;
        }else {
            $product['qt'] = 1;
            $this->cartProducts[$product['id']] = $product;
        }
    }

    public function qtChange($product, $value)
    {
        // dd($this->cartProducts[$product]);
        if (array_key_exists($product, $this->cartProducts)) {
            $this->cartProducts[$product]['qt'] = $value;
        }
    }

    public function subCat($cat_id)
    {
        $Catrgory = Category::where('id', $cat_id)->first();
        if ($Catrgory->subCats->count() !== 0) {
            $this->step = 2;
            $this->show_subCatrgories = 1;
            $this->show_Catrgories = 0;
            $this->subCatrgories = $Catrgory->subCats()->get();
        } else {
            $this->step = 3;
            $this->show_subCatrgories = 0;
            $this->show_products = 1;
            $this->show_Catrgories = 0;
            $this->products = $Catrgory->products()->get();
        }
    }

    public function step($value)
    {
        $this->show_products = $value == 3 ? 1 : 0;
        $this->show_subCatrgories = $value == 2 ? 1 : 0;
        $this->show_Catrgories = $value == 1 ? 1 : 0;
        $this->step = $value;
        if ($value - 1 == 1) {
            $this->categories = Category::where('parent_id', null)->get();
        }
    }
    public function render()
    {
        $this->categories = Category::where('parent_id', null)->get();
        return view('livewire.home');
    }
}
