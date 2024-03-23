<?php

namespace App\Http\Middleware;

use App\Modules\Localization\Services\LocalizationService;
use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    protected LocalizationService $localizationService;

    public function __construct(LocalizationService $localizationService)
    {
        $this->localizationService = $localizationService;
    }

    public function handle(Request $request, Closure $next)
    {
        $locale = $this->localizationService->getAppLocale();
        $this->localizationService->setAppLocale($locale);

        return $next($request);
    }
}
