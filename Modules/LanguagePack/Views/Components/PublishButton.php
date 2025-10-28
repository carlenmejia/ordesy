<?php

namespace Modules\LanguagePack\Views\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PublishButton extends Component
{
    public $language;
    public $languageCode;
    public $isLanguagePublished;

    /**
     * Create a new component instance.
     */
    public function __construct($language)
    {
        $this->language = $language;
        $this->languageCode = $language->language_code;
        $this->isLanguagePublished = isLanguagePublished($language->language_code);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('languagepack::components.publish-button');
    }

}
