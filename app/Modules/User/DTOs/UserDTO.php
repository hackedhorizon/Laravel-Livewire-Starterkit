<?php

namespace App\Modules\User\DTOs;

/**
 * Data Transfer Object (DTO) for representing a user entity.
 */
class UserDTO
{
    /**
     * The user's name.
     */
    private string $name;

    /**
     * The user's username.
     */
    private string $username;

    /**
     * The user's email address.
     */
    private string $email;

    /**
     * The user's password.
     */
    private string $password;

    /**
     * Create a new UserDTO instance.
     *
     * @param  string  $name  The user's name.
     * @param  string  $username  The user's username.
     * @param  string  $email  The user's email address.
     * @param  string  $password  The user's password.
     */
    public function __construct(
        string $name,
        string $username,
        string $email,
        string $password
    ) {
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the value of password.
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password.
     *
     * @param  string  $password  The user's password.
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email.
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @param  string  $email  The user's email address.
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of username.
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username.
     *
     * @param  string  $username  The user's username.
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name.
     *
     * @param  string  $name  The user's name.
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
