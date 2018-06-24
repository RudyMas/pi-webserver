<?php

namespace Repositories;

use EasyMVC\Repository\Repository;
use Models\Website;
use RudyMas\PDOExt\DBconnect;

class WebsiteRepository extends Repository
{
    /**
     * WebsiteRepository constructor.
     * @param DBconnect $db
     * @param Website|null $websites
     */
    public function __construct(DBconnect $db, Website $websites = null)
    {
        parent::__construct($db, $websites);
    }

    /**
     * Loading all websites
     */
    public function loadAllWebsites(): void
    {
        $sql = 'SELECT * FROM websites ORDER BY name';
        parent::loadAllFromTableByQuery('Website', $sql);
    }

    /**
     * Saving the new website to the database
     *
     * @param Website $website
     * @return bool
     */
    public function saveNewWebsite(Website $website): bool
    {
        if ($this->checkIfWebsiteNotExists($website)) {
            $prepare = 'INSERT INTO websites VALUES (:id, :name, :active, :https)';
            $insertWebsite = $this->db->prepare($prepare);
            $insertWebsite->bindValue(':id', $website->getId(), \PDO::PARAM_INT);
            $insertWebsite->bindValue(':name', $website->getName());
            $insertWebsite->bindValue(':active', $website->isActive(), \PDO::PARAM_BOOL);
            $insertWebsite->bindValue(':https', $website->isHttps(), \PDO::PARAM_BOOL);
            return $insertWebsite->execute();
        } else {
            return false;
        }
    }

    /**
     * Check if a website already exists
     *
     * @param Website $website
     * @return bool
     */
    private function checkIfWebsiteNotExists(Website $website): bool
    {
        $prepare = 'SELECT id FROM websites WHERE name = :name';
        $checkWebsite = $this->db->prepare($prepare);
        $checkWebsite->bindValue(':name', $website->getName());
        $checkWebsite->execute();
        return $checkWebsite->rowCount() == 0 ? true : false;
    }
}