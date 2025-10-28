<?php

namespace Modules\Subdomain\Livewire\SuperAdmin;

use Livewire\Component;
use App\Helper\Reply;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Modules\Subdomain\Entities\SubdomainSetting;

class Setting extends Component
{
    use LivewireAlert;

    public $banned_subdomain;
    public $bannedSubDomains;

    public function mount()
    {
        $settings = SubdomainSetting::first();
        $this->bannedSubDomains = $settings->banned_subdomain ?? [];
    }

    public function saveBannedSubdomain()
    {
        $this->validate([
            'banned_subdomain' => 'required|string|min:2'
        ]);

        $settings = SubdomainSetting::first();
        $bannedList = $settings->banned_subdomain ?? [];
        $settings->banned_subdomain = array_unique(array_merge([strtolower($this->banned_subdomain)], $bannedList));
        $settings->save();

        $this->bannedSubDomains = $settings->banned_subdomain;
        $this->banned_subdomain = '';

        $this->alert('success', __('subdomain::app.messages.bannedSubdomainAdded'));
    }

    public function deleteBannedSubdomain($index)
    {
        $settings = SubdomainSetting::first();
        $array = $settings->banned_subdomain;

        unset($array[$index]);
        $settings->banned_subdomain = array_values($array);
        $settings->save();

        $this->bannedSubDomains = $settings->banned_subdomain;

        $this->alert('success', __('subdomain::app.messages.bannedSubdomainDeleted'));
    }

    public function render()
    {
        return view('subdomain::livewire.super-admin.setting');
    }
}
