<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Rating extends Component
{
    public $store;
    public $message = 0;

    public function rateF($value)
    {
        if ($this->store->user) {
            $oldrate = $this->store->user->rate_my->where('user_id', auth()->user()->id ?? 0)->first();

            if ($oldrate) {
                $oldrate->update(['stars' => $value]);
            } else {
                $this->store->user->rate_my()->create([
                    'stars' => $value,
                    'user_id' => auth()->user()->id,
                ]);
            }
        }else {
            $oldrate = $this->store->rate_my->where('user_id', auth()->user()->id ?? 0)->first();

            if ($oldrate) {
                $oldrate->update(['stars' => $value]);
            } else {
                $this->store->rate_my()->create([
                    'stars' => $value,
                    'user_id' => auth()->user()->id,
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.rating');
    }
}
