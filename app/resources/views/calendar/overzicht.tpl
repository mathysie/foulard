{extends file='base.tpl'}

{block active}calendar{/block}

{block title}Tapoverzicht{/block}

{block content prepend}<h1>Tapoverzicht</h1>{/block}

{block content append}
<textarea class="w-100" rows="42" readonly>
	{$tapmail|escape}
</textarea>
{/block}
