<div class="mt-4">
    <x-label for="{{ __('subdomain::app.core.subdomain') }}" value="{{ __('subdomain::app.core.subdomain') }}" class="text-gray-700 dark:text-gray-300"/>
    <div class="relative mt-1">
        <div class="flex items-center">
            <span class="absolute left-2 px-2 py-2 text-sm text-gray-900 bg-gray-100 rounded-md shadow-sm">https://</span>
            <x-input
                id="sub_domain"
                class="w-full pl-24 pr-24 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-gray-500 dark:focus:border-gray-600 focus:ring-gray-500 dark:focus:ring-gray-600 rounded-md shadow-sm"
                type="text"
                name="sub_domain"
                :value="old('sub_domain')"
                required
                autocomplete="sub_domain"
                wire:model='sub_domain'
                placeholder="Nombre de tu restaurante"
                pattern="[a-z0-9-_]{2,20}"
                minlength="2"
                maxlength="20"
                title="2-20 lowercase letters, numbers, hyphens (-) or underscores (_)"
                oninput="this.value = this.value.toLowerCase().replace(/[^a-z0-9-_]/g, '')"
            />
            @php
                $domain = function_exists('getDomain') ? getDomain() : $_SERVER['SERVER_NAME'];
            @endphp
            <span class="absolute right-3 px-2 py-1 text-sm text-gray-900 bg-gray-100 rounded-md shadow-sm">.{{ $domain }}</span>

        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            <x-input-error for="sub_domain" class="mt-2"/>
        </p>
        <div class="flex items-center mt-2 bg-teal-50 dark:bg-teal-900/20 p-2 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600 dark:text-teal-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-xs text-teal-600 dark:text-teal-400">{{ __('subdomain::app.core.customDomainPlaceholderDescription') }}</p>
        </div>
    </div>
</div>
