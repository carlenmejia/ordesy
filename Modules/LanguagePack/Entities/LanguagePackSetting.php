<?php

namespace Modules\LanguagePack\Entities;

use App\Models\BaseModel;

class LanguagePackSetting extends BaseModel
{
    protected $guarded = ['id'];

    const MODULE_NAME = 'languagepack';
}
