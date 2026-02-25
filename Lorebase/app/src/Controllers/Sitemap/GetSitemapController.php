<?php

namespace App\Controller\Sitemap;

class GetSitemapController
{
    public function __invoke()
    {
        $baseUrl = ($_SERVER['HTTPS'] ?? 'off') === 'on'
            ? 'https://'
            : 'http://';
        $baseUrl .= $_SERVER['HTTP_HOST'];

        $xml = file_get_contents(__DIR__ . '/../../../public/sitemap.xml');
        $xml = str_replace('http://localhost:8080', $baseUrl, $xml);

        header('Content-Type: application/xml');
        echo $xml;
        exit;
    }
}
