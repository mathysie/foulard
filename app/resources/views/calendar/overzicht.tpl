{extends 'calendar/base.tpl'}

{block pagetitle}<h1>Calendar</h1>{/block}

{block breadcrumbs}
<li class="breadcrumb-item active" aria-current="page">Overzicht</li>
{/block}

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
    <a class="btn btn-primary mb-2" data-toggle="collapse" href="#nieuweAanvraag" role="button" aria-expanded="false" aria-controls="nieuweAanvraag">
        <i class="fas fa-plus"></i>&nbsp;Nieuwe aanvraag
    </a>
</form>
<div class="collapse" id="nieuweAanvraag">
    <div class="mb-2">
        <div class="card card-body">
            <form method="post" action="{route route='calendar.nieuw'}">
                <div class="form-group">
                    <label for="summary">Aanvraag</label>
                    <input type="text" class="form-control" id="summary" name="summary" placeholder="titel aanvraag">
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="startdatum">Begindatum</label>
                            <input type="date" class="form-control" id="startdatum" name="startdatum">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="starttijd">Begintijd</label>
                            <input type="time" class="form-control" id="starttijd" name="starttijd" value="17:00">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="einddatum">Einddatum</label>
                            <input type="date" class="form-control" id="einddatum" name="einddatum">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="eindtijd">Eindtijd</label>
                            <input type="time" class="form-control" id="eindtijd" name="eindtijd" value="19:30">
                        </div>
                    </div>
                </div>
                <button class="btn btn-success mb-2" type="submit"><i class="far fa-calendar-plus"></i>&nbsp;Nieuwe aanvraag</button>
            </form>
        </div>
    </div>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Datum</th>
            <th scope="col">Aanvraag</th>
            <th class="d-none d-md-table-cell"></th>
            <th class="d-none d-lg-table-cell" scope="col">KWN</th>
            <th class="d-none d-lg-table-cell" scope="col">#&nbsp;pers.</th>
            <th class="d-none d-lg-table-cell" scope="col">Tapp.</th>
            <th class="d-none d-xl-table-cell" scope="col">Min.</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        {foreach $events as $date => $aanvraag_event_lijst}
        {assign var="count" value=0}
        {foreach $aanvraag_event_lijst as $aanvraag_event}
            {assign var="count" value=($count + count($aanvraag_event->aanvragen))}
        {/foreach}
        <tr>
            <td class="{if $count > 1}align-middle{/if}" rowspan="{$count}">{$date|escape}</td>
        {foreach $aanvraag_event_lijst as $aanvraag_event}
        {assign var="count_aanvragen" value=count($aanvraag_event->aanvragen)}
        {foreach $aanvraag_event->aanvragen as $aanvraag name=foo}
            <td>{$aanvraag->summary|escape}</td>
            <td class="d-none d-md-table-cell"><span class="badge badge-pill badge-success">{$aanvraag::AANVRAGER|escape}</span></td>
            <td class="d-none d-lg-table-cell">{if $aanvraag->kwn}Ja{/if}</td>
            <td class="d-none d-lg-table-cell">{if !empty($aanvraag->pers)}{$aanvraag->pers|escape}{/if}</td>
            {if $smarty.foreach.foo.index == 0}
            <td class="{if $count > 1}align-middle{/if} d-none d-lg-table-cell {if count($aanvraag_event->tappers) >= $aanvraag_event->tap_min}table-success{else}table-danger{/if}" rowspan="{$count_aanvragen}">
                {$aanvraag_event->getTappers()|escape}
            </td>
            <td class="{if $count_aanvragen > 1}align-middle{/if} d-none d-xl-table-cell {if count($aanvraag_event->tappers) >= $aanvraag_event->tap_min}table-success{else}table-danger{/if}" rowspan="{$count_aanvragen}">
                {$aanvraag_event->tap_min|escape}
            </td>
            <td class="{if $count_aanvragen > 1}align-middle{/if}" rowspan="{$count_aanvragen}">
                <a class="btn btn-outline-primary" href="{route route='calendar.bewerk.aanvraag' id=$aanvraag_event->event->id}"><i class="far fa-edit"></i>&nbsp;Bewerk</a>
            </td>
            {/if}
        </tr>
        <tr>
        {/foreach}
        {/foreach}
        </tr>
        {/foreach}
    </tbody>
</table>
{/block}
