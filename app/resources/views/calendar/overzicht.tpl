{extends file='base.tpl'}

{block active}calendar{/block}

{block title}Tapoverzicht{/block}

{block content prepend}<h1>Tapoverzicht</h1>{/block}

{block content append}
<p class="lead">Tapschema van {$start|escape} t/m {$end|escape}.</p>
<h2>Onderwerp</h2>
<textarea class="w-100" rows="1" readonly>Tapschema t/m {$end|escape}.</textarea>
<h2>Inhoud</h2>
<textarea class="w-100" rows="{$rows}" readonly>{$tapmail|escape}</textarea>
{/block}
