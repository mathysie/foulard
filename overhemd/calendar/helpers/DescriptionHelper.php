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

    /** @var array */
    protected $patterns = [
        'aanvragen' => [
            'html'   => '/<i>%s \'([\w- ]*)\'<\/i>(?:<br>)+/i',
            'text'   => '/%s \'([\w- ]*)\'[\r\n\s]+/i',
            'config' => 'overhemd.aanvraag.text.borrel',
        ],
    ];

    public function __construct(Config $config, ?string $description)
    {
        $this->config = $config;
        $this->isHTML = $this->detectHTML($description ?? '');
    }

    public function getPattern(string $type): string
    {
        $values = $this->patterns[$type];
        $config = $this->config->get($values['config']);

        if ($this->isHTML) {
            return sprintf($values['html'], $config);
        } else {
            return sprintf($values['text'], $config);
        }
    }

    protected function detectHTML(string $str): bool
    {
        return !(strip_tags($str) == $str);
    }
}
