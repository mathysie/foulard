<?php

declare(strict_types=1);

namespace overhemd\calendar\helpers;

use mako\config\Config;

class DescriptionHelper
{
    /** @var Config */
    protected $config;

    /** @var bool */
    protected $isHTML;

    public function __construct(Config $config, ?string $description)
    {
        $this->config = $config;
        $this->isHTML = $this->detectHTML($description ?? '');
    }

    protected function detectHTML(string $str): bool
    {
        return !(strip_tags($str) == $str);
    }
}
