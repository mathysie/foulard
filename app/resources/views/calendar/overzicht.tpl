{extends 'calendar/base.tpl'}

{block pagetitle}<h1>Calendar</h1>{/block}

{block content append}
<p>Begin {$start->format('Y-m-d')} en eind {$end->format('Y-m-d')}</p>
{/block}