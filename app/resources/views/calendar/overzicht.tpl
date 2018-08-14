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
            <th class="d-none d-md-table-cell"></th>
            <th class="d-none d-lg-table-cell" scope="col">KWN</th>
            <th class="d-none d-lg-table-cell" scope="col">#&nbsp;pers.</th>
            <th class="d-none d-lg-table-cell" scope="col">Tapp.</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {foreach $events as $date => $aanvraag_event_lijst}
        {foreach $aanvraag_event_lijst as $aanvraag_event}
        {assign var="count" value=count($aanvraag_event->aanvragen)}
        <tr>
            <td {if $count > 1}class="align-middle" rowspan="{$count}"{/if}>{$date|escape}</td>
        {foreach $aanvraag_event->aanvragen as $aanvraag name=foo}
            <td>{$aanvraag->summary|escape}</td>
            <td class="d-none d-md-table-cell"><span class="badge badge-pill badge-success">{$aanvraag::AANVRAGER}</span></td>
            <td class="d-none d-lg-table-cell">{if $aanvraag->kwn}Ja{/if}</td>
            <td class="d-none d-lg-table-cell">{if !empty($aanvraag->pers)}{$aanvraag->pers}{/if}</td>
            {if $smarty.foreach.foo.index == 0}
            <td class="align-middle d-none d-lg-table-cell {if count($aanvraag_event->tappers) >= $aanvraag_event->tap_min}table-success{else}table-danger{/if}" rowspan="{$count}">
                {$aanvraag_event->getTappers()|escape}
            </td>
            <td class="align-middle" rowspan="{$count}">
                <a class="btn btn-outline-primary" href="{route route='calendar.bewerk.aanvraag' id=$aanvraag_event->event->id}"><i class="far fa-edit"></i>&nbsp;Bewerk</a>
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
