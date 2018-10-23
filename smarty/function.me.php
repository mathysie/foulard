<?php

declare(strict_types=1);

use nuno\NunoClient;

function smarty_function_me(\Smarty_Internal_Template &$template, NunoClient $nuno)
{
    $me = $nuno->isLoggedIn() ? $nuno->me() : null;

    $template->assign('me', $me);
}
