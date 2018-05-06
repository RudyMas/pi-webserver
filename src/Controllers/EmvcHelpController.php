<?php

namespace Controllers;

use EasyMVC\Controller\Controller;
use Exception;
use RudyMas\FileManager\FileManager;

class EmvcHelpController extends Controller
{
    private $Core;

    /**
     * EmvcHelpController constructor.
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->Core = $args['Core'];
        /* $bestand = new FileManager();

        $output = "192.168.0.2\tpi-webserver phpmyadmin rmfoto rmsoft\n";
        $output .= "fe80::2\t\tpi-webserver phpmyadmin rmfoto rmsoft\n";
        $bestand->saveLittleFile($output, '/etc/dnsmasq_hosts.conf');
        $test = shell_exec('sudo service dnsmasq restart 2>&1');
        $test = '';
        $test .= shell_exec('ls -l /etc/');
        $test .= shell_exec('ls -l'); */
    }

    public function welcomeAction()
    {
        try {
            $this->render('index.twig', [], 'TWIG');
        } catch (Exception $e) {
            $this->checkArray($e->getMessage());
        }
    }
}