<?php

class User{
    private int $id;
    private ?string $name;
    private ?string $username;
    private ?string $password = "";
    private string $email;
    private ?string $phone = "";
    private ?bool $role;
    private bool $active;
    private ?array $POSTS;

    /**
     * @param int $id
     * @param string|null $name
     * @param string|null $username
     * @param string $email
     * @param array|null $POSTS
     */
    public function __construct(int $id, ?string $name, ?string $username, string $email, ?array $POSTS)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->role = 0; // Author
        $this->active = 1;
        $this->POSTS = $POSTS;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */

    /**
     * @return bool
     */
    public function getRole(): bool
    {
        return $this->role;
    }

    /**
     * @param bool $role
     */

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return array
     */
    public function getPOSTS(): array
    {
        return $this->POSTS;
    }

    public function setRole($role){
        $this->role = $role;
    }
}
