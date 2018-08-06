<?php

use mako\http\routing\URLBuilder;

/**
 * Find the actual URL for a given route.
 *
 * @param array      $params  The parameters for the route.
 *                            "route" is taken as the name of the route,
 *                            all others are passed to the URL builder.
 * @param URLBuilder $builder
 *
 * @return string the URL
 *
 * @author dlf/felt
 */
function smarty_function_route(array $params, URLBuilder $builder)
{
    $name = $params['route'];
    unset($params['route']);

    return $builder->toRoute($name, $params);
}
