<?php

namespace Modules\Subdomain\Entities;

use Illuminate\Database\Eloquent\Model;

class SubdomainSetting extends Model
{

    protected $table = 'sub_domain_module_settings';

    protected $default = ['id'];

    protected $fillable = [
        'purchase_code',
        'supported_until',
        'banned_subdomain',
        'notify_update',
    ];

    public function setBannedSubdomainAttribute($value)
    {
        $this->attributes['banned_subdomain'] = $value ? json_encode($value) : null;
    }

    public function getBannedSubdomainAttribute()
    {
        return $this->attributes['banned_subdomain'] ? json_decode($this->attributes['banned_subdomain'], true) : null;
    }

    public static function addDefaultSubdomain($restaurant)
    {
        $restaurantName = str()->of($restaurant->name)->lower()->explode(' ')->first();
        $restaurantName = str_replace(',', '', $restaurantName);
        $serverName = getDomain();
        $restaurant->sub_domain = $restaurantName . '.' . $serverName;
        $restaurant->saveQuietly();
    }
}
