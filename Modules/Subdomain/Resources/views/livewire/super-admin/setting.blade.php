    <div class="mx-4 p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
        <h3 class="mb-4 text-xl font-semibold dark:text-white flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            @lang('subdomain::app.core.bannedSubdomains')
        </h3>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <form wire:submit.prevent="saveBannedSubdomain">
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            @lang('subdomain::app.core.enterSubdomain')
                        </label>
                        <div class="relative flex rounded-md shadow-sm">
                            <x-input
                                type="text"
                                wire:model="banned_subdomain"
                                class="block w-full rounded-none rounded-l-md focus:z-10"
                                placeholder="Enter subdomain "
                                pattern="[a-z0-9-_*%]{2,20}"
                                minlength="2"
                                maxlength="20"
                                title="2-20 lowercase letters, numbers, hyphens (-), underscores (_), asterisk (*) or percent (%)"
                                oninput="this.value = this.value.toLowerCase().replace(/[^a-z0-9-_*%]/g, '')"
                                autocomplete="off"
                                required
                            />
                            <div class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                                .{{ getDomain() }}
                            </div>
                        </div>
                        @error('banned_subdomain')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            @lang('subdomain::app.core.allowedCharacters')
                        </p>
                    </div>

                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 dark:text-gray-300">
                        <h4 class="text-lg font-medium mb-2 dark:text-white">@lang('subdomain::app.match.title')</h4>
                        <p class="text-gray-600 dark:text-gray-300">@lang('subdomain::app.match.pattern')</p>
                    </div>

                    <!-- Save Button -->
                    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-button
                            type="submit"
                            class="w-full sm:w-auto flex justify-center items-center"
                            icon="check">
                            @lang('app.save')
                        </x-button>
                    </div>
                </form>
            </div>

            <!-- Right Column -->
            <div class="border-t xl:border-t-0 xl:border-l border-gray-200 dark:border-gray-700 xl:pl-6 pt-6 xl:pt-0">
                <h4 class="text-lg font-medium mb-4 dark:text-white">
                    @lang('subdomain::app.core.bannedSubdomainList') ({{ count($bannedSubDomains) }})
                </h4>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    @lang('subdomain::app.core.subdomain')
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    @lang('app.action')
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @forelse($bannedSubDomains as $key => $subdomain)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $subdomain.'.'.getDomain() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button
                                            wire:click="deleteBannedSubdomain({{ $key }})"
                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 flex items-center"
                                        >
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            @lang('app.delete')
                                        </button>
                                    </td>
                                </tr>
                            @empty
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-8 px-4 text-center dark:text-white" colspan="9">
                                    <div class="flex flex-col items-center justify-center">

                                        <p class="text-lg font-medium text-gray-500 dark:text-gray-400">
                                            @lang('messages.noRecordFound')
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
