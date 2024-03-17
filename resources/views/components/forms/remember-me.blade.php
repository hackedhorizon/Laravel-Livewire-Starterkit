<div class="block mt-4">
    <label for="remember"
           class="inline-flex items-center">
        <x-forms.input id="remember"
                       type="checkbox"
                       variable="remember"
                       class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" />
        <span class="text-sm text-gray-600 ms-2 dark:text-gray-400">{{ __('auth.remember_me') }}</span>
    </label>
</div>
