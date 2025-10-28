@if (isLanguagePackAvailable($language->language_code))
    <x-languagepack::publish-button :language='$language' />
@endif
