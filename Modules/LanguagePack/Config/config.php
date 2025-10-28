<?php

$addOnOf = 'tabletrack';

return [
    'name' => 'LanguagePack',
    'verification_required' => true,
    'envato_item_id' => 58403963,
    'parent_envato_id' => 55116396, // TableTrack Envato ID
    'parent_min_version' => '1.2.32',
    'script_name' => $addOnOf . '-languagepack-module',
    'parent_product_name' => $addOnOf,
    'setting' => \Modules\LanguagePack\Entities\LanguagePackSetting::class,
];
