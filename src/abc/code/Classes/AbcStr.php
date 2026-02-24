<?php

namespace Azt3k\SS\Classes;

class AbcStr
{
    public string $str = '';
    public string $originalStr = '';
    public static int $wordLimit = 50;
    public static int $charLimit = 300;

    public function __construct(string $str)
    {
        $this->str = $str;
        $this->originalStr = $str;
    }

    public static function get(string $str): self
    {
        return new self($str);
    }

    public function limitWords(?int $wordLimit = null, string $overflowIndicator = '...'): self
    {
        if (!$wordLimit) {
            $wordLimit = self::$wordLimit;
        }
        $words = explode(' ', $this->str);
        if (count($words) <= $wordLimit) {
            $overflowIndicator = '';
        }
        $this->str = implode(' ', array_splice($words, 0, $wordLimit)) . $overflowIndicator;
        return $this;
    }

    public function limitChars(?int $charLimit = null, string $overflowIndicator = '...'): self
    {
        if (!$charLimit) {
            $charLimit = self::$charLimit;
        }
        if (strlen($this->str) <= $charLimit) {
            return $this;
        }
        $effectiveLimit = $charLimit - strlen($overflowIndicator);
        $this->str = substr($this->str, 0, $effectiveLimit) . $overflowIndicator;
        return $this;
    }

    public function limitCharsNoDotDot(?int $charLimit = null): self
    {
        $this->limitChars($charLimit, '');
        return $this;
    }

    public function __toString(): string
    {
        return $this->str;
    }
}
