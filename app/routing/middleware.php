<?php

declare(strict_types=1);

use app\routing\middleware\AuthorizationMiddleware;

$dispatcher->registerMiddleware('authorization', AuthorizationMiddleware::class);
