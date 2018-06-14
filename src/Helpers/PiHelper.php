<?php

namespace Helpers;

use RudyMas\FileManager\FileManager;

/**
 * Class PiHelper
 * @package Helpers
 */
class PiHelper
{
    private $fileManager;

    /**
     * PiHelper constructor.
     * @param FileManager $fileManager
     */
    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    /**
     * @param array $post
     */
    public function newWebsite(array $post): void
    {
        $output = "<VirtualHost *:80>\n";
        $output .= "\tServerName www.{$post['website']}.lan\n";
        $output .= "\tServerAlias {$post['website']}.lan\n";
        $output .= "\tServerAdmin webmaster@localhost\n";
        $output .= "\tDocumentRoot /mnt/exhdd1/wwwroot/{$post['website']}\n";
        $output .= "\tErrorLog \${APACHE_LOG_DIR}/error.log\n";
        $output .= "\tCustomLog \${APACHE_LOG_DIR}/access.log combined\n";
        $output .= "</VirtualHost>\n";
        if (isset($post['https'])) {
            $output .= "\n<VirtualHost *:443>\n";
            $output .= "\tServerName www.{$post['website']}.lan\n";
            $output .= "\tServerAlias {$post['website']}.lan\n";
            $output .= "\tServerAdmin webmaster@localhost\n";
            $output .= "\tDocumentRoot /mnt/exhdd1/wwwroot/{$post['website']}\n";
            $output .= "\tErrorLog \${APACHE_LOG_DIR}/error.log\n";
            $output .= "\tCustomLog \${APACHE_LOG_DIR}/access.log combined\n";
            $output .= "\tSSLEngine on\n";
            $output .= "\tSSLCertificateFile /etc/apache2/ssl/apache.pem\n";
            $output .= "</VirtualHost>\n";
        }

        $filename = "/etc/apache2/sites-available/{$post['website']}.lan.conf";
        if (!is_file($filename)) {
            shell_exec('sudo touch ' . $filename);
            shell_exec('sudo chown www-data:www-data ' . $filename);
        }
        $this->fileManager->saveLittleFile($output, $filename);
        shell_exec('sudo a2ensite ' . $post['website'] . '.lan');

        $folder = "/mnt/exhdd1/wwwroot/{$post['website']}";
        if (is_dir($folder)) {
            shell_exec('sudo mkdir ' . $folder);
            shell_exec('sudo chown ftpuser:ftpgroup ' . $folder);
        }

        shell_exec('sudo cp phpinfo.php ' . $folder . '/index.php');
    }

    /**
     * @param array $post
     */
    public function newFTPUser(array $post): void
    {
        $password = crypt($post['password'], 'azerty');
        $command = "sudo pure-pw useradd {$post['website']} -u ftpuser -g ftpgroup -d /mnt/exhdd1/wwwroot/{$post['website']} -m";
        print($command);
        exit;
        $test = shell_exec($command);
        print($test);
        exit;
        shell_exec('sudo pure-pw mkdb');
    }

    /**
     * @param array|null $post
     */
    public function updateDNS(array $post = null): void
    {
        if ($post != null) {
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
        shell_exec('sudo service pure-ftpd restart');
        shell_exec('sudo service dnsmasq restart');
        shell_exec('sudo service apache2 restart');
    }
}