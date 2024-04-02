<?php

namespace App\Modules\Google\Interfaces;

/**
 * Interface RecaptchaServiceInterface
 *
 * Represents a service for validating recaptcha token via a service.
 */
interface RecaptchaServiceInterface
{
    /**
     * Validate Google Recaptcha Token.
     * If the score goes over the accepted threshold, it will throw an error.
     */
    public function validateRecaptchaToken(): self;

    /**
     * Set the recaptcha token.
     *
     * @param  float  $score  The score threshold.
     */
    public function setRecaptchaToken(string $recaptchaToken): self;

    /**
     * Set the score threshold.
     *
     * @param  float  $score  The score threshold.
     */
    public function setScoreThreshold(float $score): self;

    /**
     * Set the error message attribute.
     *
     * @param  string  $errorMessageAttribute  Set the attribute name for the error message.
     */
    public function setErrorMessageAttribute(string $errorMessageAttribute): self;
}
