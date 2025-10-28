<button type="button"
    class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md group relative @if ($isLanguagePublished) text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 @else text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 @endif languagePackPublish"
    data-language-code="{{ $languageCode }}"
    data-republish="{{ $isLanguagePublished ? 'true' : 'false' }}">
    <span class="invisible group-hover:visible absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
        @lang($isLanguagePublished ? 'languagepack::app.republishButtonPopover' : 'languagepack::app.publishButtonPopover', ['language' => $language->language_name])
    </span>
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        @if ($isLanguagePublished)
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        @else
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
        @endif
    </svg>
    @if ($isLanguagePublished)
        @lang('languagepack::app.republish')
    @else
        @lang('languagepack::app.publish')
    @endif
</button>
