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
            $this->render('websites.overview.twig', ['websites' => $websites, 'passwordError' => false], 'TWIG');
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param WebsiteRepository $websiteRepository
     * @throws Exception
     */
    public function addSiteAction(WebsiteRepository $websiteRepository): void
    {
        if ($_POST['password'] != $_POST['confirmPassword']) {
            try {
                $websiteRepository->loadAllWebsites();
                $websites = $websiteRepository->getAll();
                $this->render('websites.overview.twig', ['websites' => $websites, 'passwordError' => true], 'TWIG');
            } catch (Exception $e) {
                throw $e;
            }
        }
        //$this->PiHelper->newFTPUser($_POST);
        //$this->PiHelper->updateDNS($_POST);
        //$this->PiHelper->newWebsite($_POST);
        $websiteRepository->loadAllWebsites();
        $websites = $websiteRepository->getAll();
        $this->render('websites.overview.twig', ['websites' => $websites, 'showResetModal' => true], 'TWIG');
    }

    public function resetServerAction(): void
    {
        print('Reset server!');
    }
}