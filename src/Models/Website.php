<?php

namespace Models;

class Website
{
    private $id;
    private $name;
    private $active;
    private $https;

    /**
     * Website constructor.
     * @param $id
     * @param $name
     * @param $active
     * @param $https
     */
    public function __construct(int $id, string $name, bool $active, bool $https)
    {
        $this->id = $id;
        $this->name = $name;
        $this->active = $active;
        $this->https = $https;
    }

    /**
     * @param array $data
     * @return Website
     */
    public static function new(array $data): Website
    {
        return new Website($data['id'], $data['name'], boolval($data['active']), boolval($data['https']));
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

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

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
     * @return bool
     */
    public function isHttps(): bool
    {
        return $this->https;
    }

    /**
     * @param bool $https
     */
    public function setHttps(bool $https): void
    {
        $this->https = $https;
    }
}