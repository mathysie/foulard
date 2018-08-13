{extends 'calendar/base.tpl'}

{block pagetitle}<h1>Calendar</h1>{/block}

{block content append}

<form method="get" role="form">
	<div class="form-row">
		<div class="form-group col-sm">
			<label for="start">Startdatum</label>
			<input type="date" name="start" id="start" class="form-control" placeholder="startdatum" value="{$start->formatYMD()}">
		</div>
		<div class="form-group col-sm">
			<label for="einde">Einddatum</label>
			<input type="date" name="einde" id="einde" class="form-control" placeholder="einddatum" value="{$einde->formatYMD()}">
		</div>
	</div>
	<button class="btn btn-success mb-2" type="submit"><i class="fas fa-search"></i>&nbsp;Zoeken</button>
</form>
<table class="table">
	<thead>
		<tr>
			<th scope="col">Datum</th>
			<th scope="col">Aanvraag</th>
			<th></th>
			<th scope="col">KWN</th>
			<th scope="col"># pers.</th>
			<th scope="col">Tapp.</th>
		</tr>
	</thead>
	<tbody>
		{foreach $events as $date => $aanvraag_event_lijst}
		{foreach $aanvraag_event_lijst as $aanvraag_event}
		<tr>
			<td class="align-middle" rowspan="{count($aanvraag_event->aanvragen)}">{$date|escape}</td>
		{foreach $aanvraag_event->aanvragen as $aanvraag name=foo}
			<td>{$aanvraag->summary|escape}</td>
			<td><span class="badge badge-pill badge-success">{$aanvraag::AANVRAGER}</span></td>
			<td>{if $aanvraag->kwn}Ja{/if}</td>
			<td>{if !empty($aanvraag->pers)}{$aanvraag->pers}{/if}</td>
			{if $smarty.foreach.foo.index == 0}
			<td class="align-middle {if count($aanvraag_event->tappers) > 1}table-success{else}table-danger{/if}" rowspan="{count($aanvraag_event->aanvragen)}">
				{implode(', ', $aanvraag_event->tappers)|escape}
			</td>
			{/if}
		</tr>
		<tr>
		{/foreach}
		</tr>
		{/foreach}
		{/foreach}
	</tbody>
</table>
{/block}