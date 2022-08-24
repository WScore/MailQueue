<?php

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
        return !$this->getName();
    }

    public function toJSON(): string
    {
        if ($this->hasName()) {
            return json_encode(['address' => $this->getAddress()]);
        }
        return json_encode(['address' => $this->getAddress(), 'name' => $this->getName()]);
    }
}