<?php

namespace Helpers;

use EasyMVC\Controller\Controller;
use Models\Website;
use Repositories\WebsiteRepository;
use RudyMas\FileManager\FileManager;
use RudyMas\Manipulator\Text;

/**
 * Class PiHelper
 * @package Helpers
 */
class PiHelper
{
    private $fileManager;
    private $text;

    /**
     * PiHelper constructor.
     * @param FileManager $fileManager
     * @param Text $text
     */
    public function __construct(FileManager $fileManager, Text $text)
    {
        $this->fileManager = $fileManager;
        $this->text = $text;
    }

    /**
     * Adding new website to database
     *
     * @param array $post
     * @param WebsiteRepository $websiteRepository
     * @return bool
     */
    public function addNewWebsiteToDatabase(array $post, WebsiteRepository $websiteRepository): bool
    {
        return true;
        $newWebsite = new Website(0, $post['website'], true, (isset($post['https'])) ? true : false);
        return $websiteRepository->saveNewWebsite($newWebsite);
    }

    /**
     * Adding new FTP user to Pure-FTPd database
     *
     * @param array $post
     * @return bool
     */
    public function newFTPUser(array $post): bool
    {
        return true;
        $return = true;
        exec('sudo chmod 606 /etc/pure-ftpd/pureftpd.passwd');

        $ftpPasswordFile = $this->fileManager->loadLittleFile('/etc/pure-ftpd/pureftpd.passwd');
        $lines = array_filter(explode(PHP_EOL, $ftpPasswordFile), 'strlen');
        $data = [];
        foreach ($lines as $line) {
            $data[] = explode(':', $line);
        }

        $key = array_search($post['website'], array_column($data, 0));
        if ($key == '') {
            $salt = '$6$' . $this->text->randomText(16) . '$';
            $password = crypt($post['password'], $salt);
            $data[] = [$post['website'], $password, 33, 33, '', '/wwwroot/' . $post['website'] . '/./', '', '', '', '', '', '', '', '', '', '', '', ''];

        } else {
            $return = false;
        }

        if (true === $return) {
            $output = '';
            foreach ($data as $value) {
                $output .= $value[0];
                for ($x = 1; $x < 18; $x++) {
                    $output .= ':' . $value[$x];
                }
                $output .= PHP_EOL;
            }
            $this->fileManager->saveLittleFile($output, '/etc/pure-ftpd/pureftpd.passwd');
            exec('sudo pure-pw mkdb');
        }

        exec('sudo chmod 600 /etc/pure-ftpd/pureftpd.passwd');

        return $return;
    }

    /**
     * @param array $post
     */
    public function newWebsite(array $post): void
    {
        return;
        $output = "<VirtualHost *:80>\n";
        $output .= "\tServerName {$post['website']}.local\n";
        $output .= "\tServerAdmin webmaster@localhost\n";
        $output .= "\tDocumentRoot /wwwroot/{$post['website']}\n";
        $output .= "\tErrorLog \${APACHE_LOG_DIR}/error.log\n";
        $output .= "\tCustomLog \${APACHE_LOG_DIR}/access.log combined\n";
        $output .= "</VirtualHost>\n";
        if (isset($post['https'])) {
            $output .= "\n<VirtualHost *:443>\n";
            $output .= "\tServerName {$post['website']}.local\n";
            $output .= "\tServerAdmin webmaster@localhost\n";
            $output .= "\tDocumentRoot /wwwroot/{$post['website']}\n";
            $output .= "\tErrorLog \${APACHE_LOG_DIR}/error.log\n";
            $output .= "\tCustomLog \${APACHE_LOG_DIR}/access.log combined\n";
            $output .= "\tSSLEngine on\n";
            $output .= "\tSSLCertificateFile /etc/apache2/ssl/apache.pem\n";
            $output .= "</VirtualHost>\n";
        }

        $filename = "/etc/apache2/sites-available/{$post['website']}.local.conf";
        exec('sudo touch ' . $filename);
        exec('sudo chmod 646 ' . $filename);
        $this->fileManager->saveLittleFile($output, $filename);
        exec('sudo chmod 644 ' . $filename);
        exec('sudo a2ensite ' . $post['website'] . '.local');

        $folder = "/wwwroot/{$post['website']}";
        if (!is_dir($folder)) {
            exec('sudo mkdir ' . $folder);
            exec('sudo chown www-data:www-data ' . $folder);
        }

        exec('cp phpinfo.php ' . $folder . '/index.php');
    }

    /**
     * @param array|null $post
     */
    public function updateDNS(array $post = null): void
    {
        if ($post !== null) {
            if (isset($post['https'])) $https = true; else $https = false;
            $query = "INSERT INTO websites VALUES (0, {$post['website']}, TRUE, $https)";
            $this->db->insert($query);
        }

        $listWebsites = $this->getListWebsites();

        $outputIPv4 = "192.168.0.2\t";
        $outputIPv6 = "fe80::2\t";
        foreach ($listWebsites as $listWebsite) {
            $outputIPv4 .= "$listWebsite ";
            $outputIPv6 .= "$listWebsite ";
        }
        $outputIPv4 .= "\n";
        $outputIPv6 .= "\n";

        $output = $outputIPv4 . $outputIPv6;
        $this->fileManager->saveLittleFile($output, '/etc/dnsmasq_hosts.conf');
    }

    /**
     * Resetting the server
     */
    public function resetServer(): void
    {
        exec('sudo systemctl restart dnsmasq');
        exec('sudo systemctl reload apache2');
    }
}