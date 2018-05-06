<?php

namespace Helpers;

use RudyMas\PDOExt\DBconnect;

/**
 * Class PiHelper
 * @package Helpers
 */
class PiHelper
{
    private $db;

    /**
     * PiHelper constructor.
     * @param DBconnect $db
     */
    public function __construct(DBconnect $db)
    {
        $this->db = $db;
    }

    /**
     * @return array
     */
    public function getListWebsites(): array
    {
        $query = "SELECT * FROM websites ORDER BY name";
        $this->db->query($query);
        $this->db->fetchAll();
        return $this->db->data;
    }
}