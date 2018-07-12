<?php

namespace Controllers;

use EasyMVC\Controller\Controller;
use Helpers\PiHelper;
use Repositories\WebsiteRepository;
use RudyMas\FileManager\FileManager;
use RudyMas\Manipulator\Text;

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
        $this->PiHelper = new PiHelper(new FileManager(), new Text());
    }

    /**
     * @param WebsiteRepository $websiteRepository
     * @throws \Exception
     */
    public function indexAction(WebsiteRepository $websiteRepository): void
    {
        $websiteRepository->loadAllWebsites();
        $websites = $websiteRepository->getAll();
        $this->render('websites.overview.twig', ['websites' => $websites, 'passwordError' => false], 'TWIG');
    }

    /**
     * @param WebsiteRepository $websiteRepository
     * @throws \Exception
     */
    public function addSiteAction(WebsiteRepository $websiteRepository): void
    {
        if ($_POST['password'] != $_POST['confirmPassword']) {
            $websiteRepository->loadAllWebsites();
            $websites = $websiteRepository->getAll();
            $this->render('websites.overview.twig', ['websites' => $websites, 'passwordError' => true], 'TWIG');
        }
        if (!$this->PiHelper->addNewWebsiteToDatabase($_POST, $websiteRepository)) {
            $websiteRepository->loadAllWebsites();
            $websites = $websiteRepository->getAll();
            $this->render('websites.overview.twig', ['websites' => $websites, 'duplicateError' => true], 'TWIG');
        } else {
            if ($this->PiHelper->newFTPUser($_POST)) {
                $this->PiHelper->newWebsite($_POST);
                //$this->PiHelper->updateDNS($_POST);
            }
            $websiteRepository->loadAllWebsites();
            $websites = $websiteRepository->getAll();
            $this->render('websites.overview.twig', ['websites' => $websites, 'showResetModal' => true], 'TWIG');
        }
    }

    /**
     * @throws \Exception
     */
    public function resetServerAction(): void
    {
        $redirect = [
            'sleep' => 5,
            'page' => '/websites'
        ];
        $this->render('index.twig', ['redirect' => $redirect], 'TWIG');
        $this->PiHelper->resetServer();
    }
}