<?php

namespace Modules\Subdomain\Livewire;

use App\Models\User;
use Livewire\Component;
use Modules\Subdomain\Notifications\ForgotRestaurant;

use Jantinnerezo\LivewireAlert\LivewireAlert;

class ForgetRestaurant extends Component
{

    use LivewireAlert;

    public $email;


    public function render()
    {
        return view('subdomain::livewire.forgot-restaurant');
    }

    public function submitForm()
    {
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', __('subdomain::app.messages.forgetMailFail'));
            return;
        }

        if (!$user->restaurant) {
            $this->addError('email', __('subdomain::app.messages.noRestaurantLined'));
            return;
        }

        $user->notify(new ForgotRestaurant($user->restaurant));

        $this->addError('success', __('subdomain::app.messages.forgetMailSuccess'));
    }
}
