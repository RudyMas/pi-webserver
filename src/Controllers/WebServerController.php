<?php

namespace Controllers;

use EasyMVC\Controller\Controller;
use Exception;
use Helpers\PiHelper;
use Repositories\WebsiteRepository;
use RudyMas\FileManager\FileManager;

/**
 * Class WebServerController
 * @package Controllers
 */
class WebServerController extends Controller
{
    private $PiHelper;

    /**
     * WebServerController constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        if (isset($args['PiHelper'])) $this->PiHelper = $args['PiHelper'];

        // Remove when done coding
        // $this->PiHelper = new PiHelper(new FileManager());
    }

    /**
     * @param WebsiteRepository $websiteRepository
     * @throws Exception
     */
    public function indexAction(WebsiteRepository $websiteRepository): void
    {
        $websiteRepository->loadAllWebsites();
        $websites = $websiteRepository->getAll();
        try {
            $this->render('websites.overview.twig', ['websites' => $websites], 'TWIG');
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function addSiteAction(): void
    {
        if ($_POST['password'] == $_POST['confirmPassword']) {
            $this->PiHelper->newFTPUser($_POST);
            // $this->PiHelper->updateDNS($_POST);
            // $this->PiHelper->newWebsite($_POST);
        }
        $this->redirect('/websites');
    }
}