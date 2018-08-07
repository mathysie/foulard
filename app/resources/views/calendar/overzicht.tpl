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
	<button class="btn btn-success mb-2" type="submit">Zoeken</button>
</form>
<table class="table table-striped">
	<thead>
		<tr>
			<th scope="col">Datum</th>
			<th scope="col">Aanvraag</th>
			<th></th>
			<th scope="col">KWN</th>
			<th scope="col"># pers.</th>
		</tr>
	</thead>
	<tbody>
		{foreach $events as $date => $eventlist}
		{foreach $eventlist as $event}
		<tr>
			<td>{$date|escape}</td>
			<td>{$event->event->summary|escape}</td>
			<td><span class="badge badge-pill badge-success">{$event::AANVRAGER}</span></td>
			<td>{if $event->kwn}Ja{/if}</td>
			<td>{if !empty($event->pers)}{$event->pers}{/if}</td>
		</tr>
		{/foreach}
		{/foreach}
	</tbody>
</table>
{/block}