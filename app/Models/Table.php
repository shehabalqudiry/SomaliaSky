<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    public $guarded=['id','created_at','updated_at'];

    public function get_status()
    {
        if ($this->status == 1) {
            return 'محجوز/ممتلئ';
        }else {
            return 'فارغ/متاح';
        }
    }
}
