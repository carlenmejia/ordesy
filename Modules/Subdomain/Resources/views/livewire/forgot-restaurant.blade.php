    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
        @error('success')
            <x-alert type="success">
                {{ $message }}
            </x-alert>
        @else
            <x-validation-errors class="mb-4"/>
        @enderror

        @error('success')
            <div class="flex items-center justify-center mt-4">
                <a href="{{ url('/') }}"
                   class="text-sm text-gray-500 underline underline-offset-1">
                    @lang('auth.goHome')
                </a>
            </div>
        @else
            <form method="POST" wire:submit="submitForm">
                @csrf
                <p class="mb-4 text-sm sm:text-base text-gray-700 dark:text-neutral-400">
                    {{__('subdomain::app.messages.forgotPageMessage')}}
                </p>
                <div>
                    <x-label for="email" value="{{ __('app.email') }}"/>
                    <x-input id="email" class="block mt-1 w-full"
                            type="email" name="email"
                            required
                            wire:model="email"
                            autofocus
                            autocomplete="sub_domain"/>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button class="ms-4">
                        {{ __('Submit') }}
                    </x-button>
                </div>

                <div class="mt-2 text-center">
                    <p class="my-2 text-dark-grey">{{__('subdomain::app.core.alreadyKnow')}}</p>
                    <span class="my-1">
                        <a href="{{ route('front.workspace') }}" wire:navigate
                           class="underline underline-offset-1 font-medium">
                            {{__('subdomain::app.core.backToSignin')}}
                        </a>
                    </span>
                </div>
            </form>
        @enderror
    </div>
