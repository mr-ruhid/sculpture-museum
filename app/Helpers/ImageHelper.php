<?php

if (!function_exists('img_url')) {
    function img_url(?string $path, int $width = 0, int $quality = 82): string
    {
        if (!$path) {
            return '';
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $params = [];
        if ($width > 0) {
            $params['w'] = $width;
        }
        if ($quality !== 82) {
            $params['q'] = $quality;
        }

        $url = url('/img/' . $path);

        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        return $url;
    }
}
