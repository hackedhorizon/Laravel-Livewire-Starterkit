<?php

namespace App\Modules\Auth\Interfaces;

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
     * @return void
     */
    public function validateRecaptchaToken(): void;

     /**
     * Set the recaptcha token.
     *
     * @param  float  $score  The score threshold.
     */
    public function setRecaptchaToken(string $recaptchaToken): void;

    /**
     * Set the score threshold.
     *
     * @param  float  $score  The score threshold.
     */
    public function setScoreThreshold(float $score): void;

    /**
     * Set the error message attribute.
     *
     * @param string $errorMessageAttribute Set the attribute name for the error message.
     * @return void
     */
    public function setErrorMessageAttribute(string $errorMessageAttribute): void;
}
