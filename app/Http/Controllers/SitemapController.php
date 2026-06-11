<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = config('app.url', 'https://www.therapistlysander.com');
        $locales = config('app.supported_locales', ['en', 'nl']);

        $pages = [
            ''                => '1.0',
            '/about'          => '0.8',
            '/trauma-approach' => '0.8',
            '/clinical-training' => '0.7',
            '/testimonials'   => '0.7',
            '/fees-process'   => '0.7',
            '/faq'            => '0.6',
            '/contact'        => '0.8',
            '/booking'        => '0.8',
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        foreach ($locales as $locale) {
            foreach ($pages as $path => $priority) {
                $url = $baseUrl . '/' . $locale . $path;

                $xml .= "  <url>\n";
                $xml .= "    <loc>{$url}</loc>\n";
                $xml .= "    <priority>{$priority}</priority>\n";

                // hreflang alternates
                foreach ($locales as $altLocale) {
                    $altUrl = $baseUrl . '/' . $altLocale . $path;
                    $xml .= "    <xhtml:link rel=\"alternate\" hreflang=\"{$altLocale}\" href=\"{$altUrl}\" />\n";
                }

                $xml .= "  </url>\n";
            }
        }

        $xml .= '</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
