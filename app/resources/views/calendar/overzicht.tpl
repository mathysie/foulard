{extends file='base.tpl'}

{block active}calendar{/block}

{block title}Tapoverzicht{/block}

{block content prepend}<h1>Tapoverzicht</h1>{/block}

{block content append}
<p class="lead">Tapschema van {$start|escape} t/m {$end|escape}.</p>
<div class="btn-group" role="group" aria-label="Navigatie">
    <a class="btn btn-success" href="{route route='calendar.tapmail' offset=$eerder}">
        <span class="oi oi-arrow-circle-left"></span>&nbsp;Eén week eerder
    </a>
    <a class="btn btn-success" href="{route route='calendar.tapmail' offset=$later}">
    <span class="oi oi-arrow-circle-right"></span>&nbsp;Eén week later
    </a>
</div>
<h2>Onderwerp</h2>
<textarea class="w-100" rows="1" readonly>Tapschema t/m {$end|escape}.</textarea>
<h2>Inhoud</h2>
<textarea class="w-100" rows="{$rows}" readonly>{$tapmail|escape}</textarea>
{/block}
