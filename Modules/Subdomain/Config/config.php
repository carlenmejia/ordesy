<?php

$addOnOf = 'tabletrack';

return [
    'name' => 'Subdomain',
    'verification_required' => false,
    'envato_item_id' => 56795084,
    'parent_envato_id' => 55116396, // Tabletrack Envato ID
    'parent_min_version' => '1.2.1',
    'script_name' => $addOnOf . '-subdomain-module',
    'parent_product_name' => $addOnOf,
    'setting' => \Modules\Subdomain\Entities\SubdomainSetting::class,
];
