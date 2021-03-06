<?php

declare(strict_types=1);

return [
    'templateDir' => MAKO_APPLICATION_PATH . '/resources/views',
    'compileDir'  => MAKO_APPLICATION_PATH . '/storage/smarty/',
    'pluginDirs'  => [dirname(MAKO_APPLICATION_PATH) . '/smarty'],
];
