<?php

namespace Modules\LanguagePack\Console;

use Illuminate\Console\Command;
use Modules\LanguagePack\Http\Controllers\LanguagePackController;

class PublishTranslationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'languagepack:publish-translation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish translations to the application and modules from the LanguagePack module';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        (new LanguagePackController())->publishAll();
    }
}
