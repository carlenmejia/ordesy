<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
    <x-validation-errors class="mb-4" />

    @session('status')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ $value }}
        </div>
    @endsession

    <form method="POST" wire:submit="submitForm">
        @csrf

        <p class="mb-4 text-sm sm:text-base text-gray-700 dark:text-neutral-400">
            @lang('subdomain::app.core.signInTitle')
        </p>

        <div>
            <x-label for="sub_domain" value="{{ __('Restaurant URL') }}" />
            <div class="relative mt-1">
                <div class="flex items-center">
                    <span class="absolute left-2 px-2 py-2 text-sm text-gray-900 bg-gray-100 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">https://</span>
                    <x-input
                        id="sub_domain"
                        class="w-full pl-24 pr-24 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-gray-500 dark:focus:border-gray-600 focus:ring-gray-500 dark:focus:ring-gray-600 rounded-md shadow-sm"
                        type="text"
                        name="sub_domain"
                        wire:model="subdomain"
                        required
                        autofocus
                        autocomplete="sub_domain"
                        placeholder="Nombre de tu restaurante"
                        pattern="[a-z0-9-_]{2,20}"
                        minlength="2"
                        maxlength="20"
                        title="2-20 lowercase letters, numbers, hyphens (-) or underscores (_)"
                        oninput="this.value = this.value.toLowerCase().replace(/[^a-z0-9-_]/g, '')"
                    />
                    <span class="absolute right-3 px-2 py-1 text-sm text-gray-900 bg-gray-100 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">.{{ getDomain() }}</span>
                </div>
                <div class="flex items-center mt-2 bg-teal-50 dark:bg-teal-900/20 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-teal-600 dark:text-teal-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                        <p class="text-xs text-teal-600 dark:text-teal-400">{{ __('subdomain::app.core.customDomainPlaceholderDescription') }}</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button class="ms-4">
                @lang('subdomain::app.core.continue')
            </x-button>
        </div>

        <div class="mt-2 text-center">
            <p class="my-2 text-dark-grey">
                @lang('subdomain::app.core.signInTitle')
            </p>
            <span class="my-1">
                <a href="{{ route('front.forgot-restaurant') }}" wire:navigate class="underline underline-offset-1 font-medium">
                    {{__('subdomain::app.messages.findRestaurantUrl')}}
                </a>
            </span>
        </div>

        @if (!global_setting()->disable_landing_site)
            <div class="flex items-center justify-center mt-4">
            <a href="{{ url('/') }}"
               class="text-sm text-gray-500 underline underline-offset-1">

                    @lang('auth.goHome')
                </a>
            </div>
        @else
            <div class="flex items-center justify-center mt-4">
                <a href="{{ route('restaurant_signup') }}"
                   class="text-sm text-gray-500 underline underline-offset-1">

                    @lang('app.createAccount')
                </a>
            </div>
        @endif

    </form>
</div>
