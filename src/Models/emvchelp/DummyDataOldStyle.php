<?php

namespace Models\emvchelp;

/**
 * Class DummyDataOldStyle
 *
 * This is an example of a standard model class.
 * Use this kind of model if you don't want to use the EasyMVC extension for model classes.
 *
 * @package Models
 */
class DummyDataOldStyle
{
    private $id;
    private $firstName = '';
    private $lastName = '';
    private $phone = '';
    private $email = '';

    /**
     * DummyDataOldStyle constructor.
     *
     * @param int $id
     * @param string $firstName
     * @param string $lastName
     * @param string $phone
     * @param string $email
     */
    public function __construct(int $id, string $firstName, string $lastName, string $phone, string $email)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->phone = $phone;
        $this->email = $email;
    }

    /**
     * @param array $data
     * @return DummyDataOldStyle
     */
    public static function new(array $data): DummyDataOldStyle
    {
        return new self($data['id'], $data['first_name'], $data['last_name'], $data['phone'], $data['email']);
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
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
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
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
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
}
