<?php

namespace Azt3k\SS\Classes;

class AbcURL
{
    public string $URL;
    public string $originalURL;

    public function __construct(?string $URL = null)
    {
        if (!$URL) {
            $URL = $_SERVER['REQUEST_URI'];
        }
        $this->URL = $URL;
        $this->originalURL = $URL;
    }

    public static function get(?string $URL = null): self
    {
        return new self($URL);
    }

    public function q(array $data): self
    {
        $url = parse_url($this->URL);
        !empty($url['query']) ? parse_str($url['query'], $r) : $r = [];
        $data = array_merge($r, $data);
        $url['query'] = http_build_query($data);
        $this->URL = self::buildURL($url);
        return $this;
    }

    public static function buildURL(array $data): string
    {
        $url = '';
        if (!empty($data['scheme'])) {
            $url .= $data['scheme'] . '://';
        }
        if (!empty($data['user']) && !empty($data['pass'])) {
            $url .= $data['user'] . ':' . $data['pass'] . '@';
        }
        if (!empty($data['host'])) {
            $url .= $data['host'];
        }
        if (!empty($data['path'])) {
            $url .= $data['path'];
        }
        if (!empty($data['query'])) {
            $url .= '?' . $data['query'];
        }
        if (!empty($data['fragment'])) {
            $url .= '#' . $data['fragment'];
        }
        return $url;
    }
}
