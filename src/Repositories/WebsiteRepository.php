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

    public function loadAllWebsites()
    {
        $sql = 'SELECT * FROM websites ORDER BY name';
        parent::loadAllFromTableByQuery('Website', $sql);
    }
}