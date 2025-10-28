<?php

namespace Modules\Subdomain\Livewire;

use Livewire\Component;

class Workspace extends Component
{

    public $subdomain;


    public function render()
    {
        return view('subdomain::livewire.workspace');
    }

    public function submitForm()
    {
        $this->validate([
            'subdomain' => 'required',
        ]);

        return redirect(str_replace(request()->getHost(), $this->subdomain . '.' . getDomain(), route('login')));
    }
}
