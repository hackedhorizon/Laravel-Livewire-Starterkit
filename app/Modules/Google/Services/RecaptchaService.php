<?php

namespace App\Modules\Google\Services;

use App\Modules\Google\Interfaces\RecaptchaServiceInterface;
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
        $this->errorMessageAttribute = '';
    }

    /**
     * {@inheritdoc}
     */
    public function validateRecaptchaToken(): self
    {
        $query = http_build_query([
            'secret' => config('services.google_captcha.secret_key'),
            'response' => $this->recaptchaToken,
        ]);

        $response = Http::post('https://www.google.com/recaptcha/api/siteverify?'.$query);
        $captchaLevel = $response->json('score');

        throw_if($captchaLevel <= $this->threshold, ValidationException::withMessages([
            $this->errorMessageAttribute => __('validation.recaptcha_failed'),
        ]));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRecaptchaToken(string $recaptchaToken): self
    {
        $this->recaptchaToken = $recaptchaToken;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setScoreThreshold(float $score): self
    {
        $this->threshold = $score;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorMessageAttribute(string $errorMessageAttribute): self
    {
        $this->errorMessageAttribute = $errorMessageAttribute;

        return $this;
    }
}
