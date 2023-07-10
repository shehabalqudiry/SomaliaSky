<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserSkill extends Component
{
    public $user;
    public $skill;
    public $editskill;
    public $eskill;
    public $rule = [];
    public $formShow = 0;
    public $formEditShow = 0;

    public function rules()
    {
        return $this->rule;
    }

    public function delete($id)
    {
        $this->formShow = 0;
        $this->formEditShow = 0;
        $this->editskill = null;
        $this->user->skills()->where('id', $id)->first()->delete();
    }
    public function addskill()
    {
        if ($this->formShow == 1) {
            $this->rule['skill'] = 'required|string';
        }
        $this->validate();
        $this->user->skills()->create(['name' => $this->skill]);
        $this->skill = '';
        $this->formShow = 0;
    }
    public function formEdit($id)
    {
        $this->formEditShow = 1;
        if ($this->formEditShow == 1) {
            $this->rule['eskill'] = 'required|string';
            $this->editskill = $this->user->skills()->where('id', $id)->first();
            $editskill = $this->user->skills()->where('id', $id)->first();
            $this->eskill = $editskill->name;
        }
    }
    public function editskill()
    {
        $this->validate();

        $this->editskill->update(['name' => $this->eskill]);
        $this->eskill = '';
        $this->formEditShow = 0;
    }
    public function render()
    {
        return view('livewire.user-skill');
    }
}
