<?php

namespace App\Modules\Auth\Services;

use App\Modules\Auth\Interfaces\RecaptchaServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class RecaptchaService implements RecaptchaServiceInterface
{
    private float $threshold;
    private ?string $recaptchaToken;
    private string $errorMessageAttribute;

    public function __construct()
    {
        $this->threshold = 0.0;
        $this->recaptchaToken = null;
        $this->errorMessageAttribute = "";
    }

    /**
     * {@inheritdoc}
     */
    public function validateRecaptchaToken(): void
    {
        $query = http_build_query([
            'secret' => config('services.google_captcha.secret_key'),
            'response' => $this->recaptchaToken,
        ]);

        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?' . $query);
        $captchaLevel = $response->json('score');

        throw_if($captchaLevel <= $this->threshold, ValidationException::withMessages([
            $this->errorMessageAttribute => __('validation.recaptcha_failed')
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function setRecaptchaToken(string $recaptchaToken): void
    {
        $this->recaptchaToken = $recaptchaToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setScoreThreshold(float $score): void
    {
        $this->threshold = $score;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorMessageAttribute(string $errorMessageAttribute): void
    {
        $this->errorMessageAttribute = $errorMessageAttribute;
    }
}
