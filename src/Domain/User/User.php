<?php

declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Shared\ValueObject\DateTime;
use App\Domain\User\Exception\InvalidCredentialsException;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use App\Domain\User\ValueObject\Email;
use App\User\Domain\Event\UserSignedIn;
use Ramsey\Uuid\UuidInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class User
{
    private UuidInterface $uuid;

    private Credentials $credentials;

    private ?DateTime $createdAt;

    private ?DateTime $updatedAt;

    private ?DateTime $lastLogInAt;


    public function __construct(?UuidInterface $uuid = null, ?Credentials $credentials = null)
    {
        if ($uuid)
            $this->uuid = $uuid;
        if ($credentials)
            $this->credentials = $credentials;

        $this->createdAt = new DateTime();
    }

    static public function create(UuidInterface $uuid, Credentials $credentials) : self
    {
        return new self($uuid, $credentials);
    }


    public function setEmail(Email $email): void
    {
        $this->credentials->email = $email;
    }

    public function setHashedPassword(HashedPassword $hashedPassword): void
    {
        $this->credentials->hashedPassword = $hashedPassword;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt->toString();
    }

    public function getUpdatedAt(): ?string
    {
        return isset($this->updatedAt) ? $this->updatedAt->toString() : null;
    }

    public function getLastLogInAt(): ?string
    {
        return isset($this->lastLogInAt) ? $this->lastLogInAt->toString() : null;
    }

    public function getEmail(): string
    {
        return $this->credentials->email->toString();
    }

    public function getUuid(): string
    {
        return $this->uuid->toString();
    }

    public function uuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getAggregateRootId(): string
    {
        return $this->uuid->toString();
    }

    public function signIn(string $plainPassword) : bool
    {
        if (!$this->credentials->password->match($plainPassword)) {
            throw new InvalidCredentialsException('Invalid credentials entered.');
        }

        $this->lastLogInAt = new DateTime();

        return true;
    }

}
