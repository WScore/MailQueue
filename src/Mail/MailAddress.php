<?php
declare(strict_types=1);

namespace WScore\MailQueue\Mail;

use function json_encode;

class MailAddress
{
    private $address;
    private $name;

    public function __construct(string $address, string $name = null)
    {
        $this->address = $address;
        $this->name = $name;
    }

    public static function fromArray(array $json): MailAddress
    {
        $json += ['name' => null];
        return new self($json['address'], $json['name']);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getName(): string
    {
        return (string) $this->name;
    }

    public function hasName(): bool
    {
        return (bool) $this->getName();
    }

    public function toJSON(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toArray(): array
    {
        if ($this->hasName()) {
            return ['address' => $this->getAddress(), 'name' => $this->getName()];
        }
        return ['address' => $this->getAddress()];
    }
}