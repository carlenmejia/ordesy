<?php

namespace Modules\LanguagePack\Http\Controllers;

use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Models\LanguageSetting;
use Illuminate\Support\Facades\File;
use Modules\LanguagePack\Http\Requests\PublishLanguageRequest;

class LanguagePackController extends Controller
{

    public function publishAll()
    {
        $languages = LanguageSetting::all();

        try {
            foreach ($languages as $language) {
                $this->publishLanguage($language->language_code);
            }
        } catch (\Throwable $th) {
            return Reply::error($th->getMessage());
        }

        return Reply::success(__('languagepack::messages.allLanguagePublished'));
    }

    public function publish(PublishLanguageRequest $request)
    {
        try {
            $this->publishLanguage($request->languageCode);
        } catch (\Throwable $th) {
            return Reply::error($th->getMessage());
        }
        return Reply::success(__('languagepack::messages.languagePublished'));
    }

    private function publishLanguage($languageCode)
    {
        $path = lang_path($languageCode);

        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }

        $sourcePath = languagePackPath($languageCode);

        if (File::isDirectory($sourcePath)) {
            File::copyDirectory($sourcePath, $path);
        }

        $modules = \Nwidart\Modules\Facades\Module::all();

        foreach ($modules as $moduleName => $module) {
            $this->publishModuleLanguage($moduleName, $languageCode);
        }
    }

    private function publishModuleLanguage($module, $languageCode)
    {
        $path = module_path($module, 'Resources/lang/' . $languageCode);

        if (File::isDirectory($path)) {
            File::deleteDirectory($path);
        }

        $sourcePath = languagePackPath($languageCode, $module);

        if (File::isDirectory($sourcePath)) {
            File::copyDirectory($sourcePath, $path);
        }
    }
}
