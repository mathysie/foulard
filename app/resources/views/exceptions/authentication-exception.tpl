{extends file="base.tpl"}

{block pagetitle}<h1>Geen authenticatie met Google</h1>{/block}

{block content append}
<p class="lead">De crendentials voor de authenticatie zijn niet bekend. Zorg op de volgende manier ervoor dat je met Google contact kan maken.</p>
<h2>Wel credentials</h2>
<p>Heb je de credentials al gedownload? Verplaats dan het bestand <code>credentials.json</code> in de map <code>foulard/google/</code>.</p>
<h2>Geen credentials</h2>
<p>Heb je de credentials nog niet gedownload? Zorg er eerst voor dat je toegang hebt tot een test-agenda. Doe dan het volgende:</p>
<ul>
	<li>Volg de <a href="https://developers.google.com/calendar/quickstart/php" target="_blank">instructies van Google</a>. Log in bij Google met een account dat toegang heeft tot de FooTest-agenda.</li>
	<li>Plaats de bestanden <code>credentials.json</code> en <code>token.json</code> in de map <code>foulard/google/</code>.</li>
</ul>
{/block}