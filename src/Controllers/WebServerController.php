<?php

namespace Controllers;

use EasyMVC\Controller\Controller;
use Exception;
use Helpers\PiHelper;

/**
 * Class WebServerController
 * @package Controllers
 */
class WebServerController extends Controller
{
    private $DB;
    private $PiHelper;

    /**
     * WebServerController constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        $Core = $args['Core'];
        if (isset($Core->DB['DBconnect'])) $this->DB = $Core->DB['DBconnect'];
        $this->PiHelper = new PiHelper($this->DB);
    }

    /**
     * @throws Exception
     */
    public function indexAction()
    {
        $listServers = $this->PiHelper->getListWebsites();
        try {
            $this->render('websites.overview.twig', ['websites' => $listServers], 'TWIG');
        } catch (Exception $e) {
            throw $e;
        }
    }
}