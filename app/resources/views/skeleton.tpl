<!DOCTYPE html>
<html lang="nl">
    <head>
        <title>{block title}FooBar{/block} - Foulard</title>
        {include '_head.tpl'}

        {block head}{/block}
    </head>
    <body>
        {capture 'active'}{block active}{/block}{/capture}
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light bg-light border rounded">
                <a class="navbar-brand" href="/">
                    <img src="/assets/img/logo.svg" width="30" height="30">
                    &nbsp;Foulard
                </a>
                {if getenv('FOULARD_PROD') !== '1'}
                <button class="btn btn-danger" type="button">Debug</button>
                {/if}
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarDropdown" aria-controls="navbarDropdown" aria-expanded="false">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarDropdown">
                    <div class="navbar-nav">
                        <a class="nav-item nav-link {if $smarty.capture.active=='tapschema'}active{/if}" href="{route route='tapschema.tapmail'}">Tapschema</a>
                    </div>
                </div>
            </nav>
            {block content}
            {/block}
        </div>
    </body>
</html>
