{extends 'calendar/base.tpl'}

{block pagetitle}<h1>'{$aanvraag_event->getAanvragenLijst()|escape}' bewerken</h1>{/block}

{block content append}
<div class="row">
    <div class="col-12 col-lg-8">
        <form method="post">
            <div class="form-group">
                <div class="form-row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="startdatum">Begindatum</label>
                            <input type="date" class="form-control" id="startdatum" name="startdatum" value="{$aanvraag_event->start->formatYMD()|escape}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="starttijd">Begintijd</label>
                            <input type="time" class="form-control" id="starttijd" name="starttijd" value="{$aanvraag_event->start->formatTime()|escape}">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="einddatum">Einddatum</label>
                            <input type="date" class="form-control" id="einddatum" name="einddatum" value="{$aanvraag_event->eind->formatYMD()|escape}">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="eindtijd">Eindtijd</label>
                            <input type="time" class="form-control" id="eindtijd" name="eindtijd" value="{$aanvraag_event->eind->formatTime()|escape}">
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <label for="tappers">Tappers</label>
                        <input type="text" class="form-control" id="tappers" name="tappers" value="{$aanvraag_event->getTappers()|escape}" aria-describedby="tappersHelp">
                        <small class="form-text" id="tappersHelp">Scheid de namen van tappers met een komma (,).</small>
                    </div>
                    <div class="col-auto">
                        <label for="tap-min">Minimum</label>
                        <input type="number" class="form-control" id="tap-min" name="tap_min" value="{$aanvraag_event->tap_min|escape}">
                    </div>
                </div>
            </div>

            {foreach $aanvraag_event->aanvragen as $aanvraag name=foo}
            {assign var="index" value=$smarty.foreach.foo.index}
            <div class="form-group">
                <h2 id="aanvraag-{$index}">Aanvraag '{$aanvraag->summary|escape}'</h2>
                <div class="form-group">
                    <label for="summary-{$index}">Omschrijving</label>
                    <input type="text" class="form-control" id="summary-{$index}" name="summary-{$index}" value="{$aanvraag->summary|escape}" placeholder="Aanvraag">
                </div>
                <div class="form-group row">
                    <label for="aanvrager-{$index}" class="col-auto col-form-label">Aanvrager:</label>
                    <div class="col">
                        <input type="text" class="form-control-plaintext" id="aanvrager-{$index}" value="{$aanvraag::AANVRAGER|escape}" readonly>
                    </div>
                </div>
                <div class="form-group">
                    <label for="contactpersoon-{$index}">Contactpersoon</label>
                    <input type="text" class="form-control" id="contactpersoon-{$index}" name="contactpersoon-{$index}" value="{$aanvraag->contactpersoon|escape}" placeholder="contactpersoon">
                </div>
                <div class="form-group">
                    <label for="sap-{$index}">SAP-nummer</label>
                    <input type="number" class="form-control" id="sap-{$index}" name="sap-{$index}" value="{$aanvraag->sap|escape}" placeholder="SAP-nummer">
                </div>
                <div class="form-row">
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="kwn-{$index}">Met KWN</label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="kwn-{$index}-ja" name="kwn-{$index}" value="1" {if $aanvraag->kwn}checked{/if}>
                                <label class="form-check-label" for="kwn-{$index}-ja">
                                    Ja
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" id="kwn-{$index}-nee" name="kwn-{$index}" value="0" {if !$aanvraag->kwn}checked{/if}>
                                <label class="form-check-label" for="kwn-{$index}-nee">
                                    Nee
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="pers-{$index}">Aantal personen</label>
                            <input type="number" class="form-control" id="pers-{$index}" name="pers-{$index}" value="{$aanvraag->pers|escape}" placeholder="# pers.">
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="kwn-port-{$index}">Aantal porties KWN</label>
                            <input type="number" class="form-control" id="kwn-port-{$index}" name="kwn-port-{$index}" value="{$aanvraag->kwn_port|escape}" placeholder="# porties">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description-{$index}">Bijzonderheden</label>
                    <textarea class="form-control" name="description-{$index}" id="description-{$index}" placeholder="Bijzonderheden" aria-describedby="bijzonderhedenHelp">{$aanvraag->description|escape}</textarea>
                    <small class="form-text" id="bijzonderhedenHelp">Zet 'Persoonlijk' op de bovenste regel als de aanvraag een persoonlijke aanvraag moet zijn.</small>
                </div>
            </div>
            {/foreach}
            <button class="btn btn-success" type="submit"><i class="far fa-save"></i>&nbsp;Opslaan</button>
        </form>
    </div>
</div>
{/block}