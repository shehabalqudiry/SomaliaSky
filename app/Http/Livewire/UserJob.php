<?php

namespace App\Http\Livewire;

use Livewire\Component;

class UserJob extends Component
{
    public $user;
    public $job;
    public $editjob;
    public $ejob;
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
        $this->editjob = null;
        $this->user->jobs()->where('id', $id)->first()->delete();
    }
    public function addJob()
    {
        if ($this->formShow == 1) {
            $this->rule['job'] = 'required|string';
        }
        $this->validate();
        $this->user->jobs()->create(['name' => $this->job]);
        $this->job = '';
        $this->formShow = 0;
    }
    public function formEdit($id)
    {
        $this->formEditShow = 1;
        if ($this->formEditShow == 1) {
            $this->rule['ejob'] = 'required|string';
            $this->editjob = $this->user->jobs()->where('id', $id)->first();
            $editjob = $this->user->jobs()->where('id', $id)->first();
            $this->ejob = $editjob->name;
        }
    }
    public function editJob()
    {
        $this->validate();

        $this->editjob->update(['name' => $this->ejob]);
        $this->ejob = '';
        $this->formEditShow = 0;
    }
    public function render()
    {
        return view('livewire.user-job');
    }
}
