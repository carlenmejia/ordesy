<div class="w-full">
    <div class="flex items-center space-x-4 w-full">
        <div class="w-1/3">
            <x-label class="mt-3" fieldId="domain" fieldRequired="true" :fieldLabel="__('subdomain::app.core.domainType')"></x-label>
            <x-select name="domain" id="domain" class="block mt-1 w-full domain-type" wire:model.live='domain'>
                <option
                    @if(isset($restaurant))
                        @selected(str($restaurant->sub_domain)->endsWith(getDomain()))
                    @endif
                    value="{{ '.'. getDomain() }}"
                >
                    {{ __('subdomain::app.core.subdomain') }}
                </option>
                <option
                    @if(isset($restaurant))
                        @selected(!str($restaurant->sub_domain)->endsWith(getDomain()))
                    @endif
                    value=""
                >
                    @lang('subdomain::app.core.customDomain')
                </option>
            </x-select>
        </div>

        <div class="w-2/3">
            <x-label class="mt-3" fieldRequired="true" :fieldLabel="__('subdomain::app.core.domain')"></x-label>
            <div class="relative flex rounded-md shadow-sm">
                <x-input
                    id="sub_domain"
                    class="block w-full rounded-none rounded-l-md focus:z-10"
                    type="text"
                    name="sub_domain"
                    :value="isset($restaurant) ? str_replace('.'.getDomain(),'',$restaurant->sub_domain) : old('sub_domain')"
                    wire:model='sub_domain'
                    required
                    {{-- :pattern="$domain ? '[a-z0-9\-_]{2,20}' : '[a-z0-9\-_.]+\.[a-z]{2,}'" --}}
                    :minlength="$domain ? '2' : '4'"
                    :maxlength="$domain ? '20' : '253'"
                    :title="$domain ? '2-20 lowercase letters, numbers, hyphens (-) or underscores (_)' : 'Valid domain name (e.g., example.com)'"
                    :oninput="$domain ? 'this.value = this.value.toLowerCase().replace(/[^a-z0-9-_]/g, \'\')' : ''"
                    autocomplete="sub_domain"
                    :placeholder="$domain ? __('subdomain::app.core.subdomain') : __('subdomain::app.core.customDomainPlaceholder')"
                />

                @if($domain)
                    <div class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-700 dark:text-gray-300">
                        .{{ getDomain() }}
                    </div>
                @endif

            </div>

        </div>
    </div>
    @if($domain)
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            @lang('subdomain::app.core.allowedCharacters')
        </p>
    @else
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            @lang('subdomain::app.core.allowedCharactersDomain')
        </p>
    @endif
    <x-input-error for="sub_domain" class="mt-2"/>

</div>
