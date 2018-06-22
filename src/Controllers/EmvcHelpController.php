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
        $bestand = new FileManager();

        shell_exec('sudo chmod 660 /etc/pure-ftpd/pureftpd.passwd');
        $test = shell_exec('cat /etc/pure-ftpd/pureftpd.passwd 2>&1');
        print('<pre>');
        print($test);
        print('</pre>');
        $string = $bestand->loadLittleFile('/etc/pure-ftpd/pureftpd.passwd');
        print($string);
        $explString = array_filter(explode(PHP_EOL, $string), 'strlen');
        print('<pre>');
        print_r($explString);
        print('</pre>');
        $array = [];
        foreach ($explString as $string) {
            $array[] = explode(':', $string);
        }
        unset($array['2']);
        $array = array_values($array);
        print('<pre>');
        print_r($array);
        print('</pre>');
        exit;

        $output = "192.168.0.2\tpi-webserver phpmyadmin rmfoto rmsoft\n";
        $output .= "fe80::2\t\tpi-webserver phpmyadmin rmfoto rmsoft\n";
        $bestand->saveLittleFile($output, '/etc/dnsmasq_hosts.conf');
        // $test = shell_exec('sudo service dnsmasq restart 2>&1');
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