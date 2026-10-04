<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Page;
use App\Models\Sculpture;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $languages = Language::where('is_active', true)->orderBy('is_default', 'desc')->pluck('code')->toArray();

        if (empty($languages)) {
            $languages = ['en'];
        }

        $defaultLocale = $languages[0];

        $urls = [];

        $staticRoutes = [
            ['path' => '', 'priority' => '1.0', 'changefreq' => 'weekly'],
            ['path' => 'sculptures', 'priority' => '0.9', 'changefreq' => 'weekly'],
            ['path' => 'about', 'priority' => '0.7', 'changefreq' => 'monthly'],
            ['path' => 'contact', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ];

        foreach ($staticRoutes as $route) {
            foreach ($languages as $locale) {
                $urls[] = [
                    'loc' => $this->buildUrl($locale, $route['path']),
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => $route['changefreq'],
                    'priority' => $route['priority'],
                    'alternates' => $this->alternates($languages, $route['path']),
                ];
            }
        }

        $sculptures = Sculpture::where('is_published', true)
            ->select('slug', 'updated_at')
            ->get();

        foreach ($sculptures as $sculpture) {
            $path = 'sculptures/' . $sculpture->slug;
            foreach ($languages as $locale) {
                $urls[] = [
                    'loc' => $this->buildUrl($locale, $path),
                    'lastmod' => $sculpture->updated_at?->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                    'alternates' => $this->alternates($languages, $path),
                ];
            }
        }

        $pages = Page::where('is_published', true)
            ->where('slug', '!=', 'home')
            ->select('slug', 'updated_at')
            ->get();

        foreach ($pages as $page) {
            foreach ($languages as $locale) {
                $urls[] = [
                    'loc' => $this->buildUrl($locale, $page->slug),
                    'lastmod' => $page->updated_at?->toAtomString() ?? now()->toAtomString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                    'alternates' => $this->alternates($languages, $page->slug),
                ];
            }
        }

        $xml = $this->buildXml($urls, $defaultLocale);

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    protected function buildUrl(string $locale, string $path): string
    {
        $base = rtrim(config('app.url'), '/');
        $path = trim($path, '/');

        return $path === ''
            ? "{$base}/{$locale}"
            : "{$base}/{$locale}/{$path}";
    }

    protected function alternates(array $languages, string $path): array
    {
        $out = [];
        foreach ($languages as $locale) {
            $out[$locale] = $this->buildUrl($locale, $path);
        }
        return $out;
    }

    protected function buildXml(array $urls, string $defaultLocale): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n";
            $xml .= '    <lastmod>' . $url['lastmod'] . "</lastmod>\n";
            $xml .= '    <changefreq>' . $url['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $url['priority'] . "</priority>\n";

            foreach ($url['alternates'] as $locale => $altUrl) {
                $xml .= '    <xhtml:link rel="alternate" hreflang="' . $locale . '" href="' . htmlspecialchars($altUrl, ENT_XML1) . "\"/>\n";
            }

            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
