<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title">Datenbank-Integrität</h5>
    </div>
    <div class="card-body">
        <p>Prüfung der MySQL-Trigger und Events, die für den reibungslosen Betrieb (RFID-Anbindung & Rankings) erforderlich sind.</p>
        
        {if $integrity.status}
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> Alle Datenbank-Komponenten (Trigger & Events) sind korrekt installiert.
            </div>
        {else}
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle"></i> <strong>Achtung:</strong> Es fehlen wichtige Komponenten:
                <ul>
                    {foreach from=$integrity.missing item=item}
                        <li>{$item}</li>
                    {/foreach}
                </ul>
            </div>
        {/if}
        
        <div class="mt-3">
            <a href="{$actionurl}&action=repair_integrity" class="btn btn-primary">
                <i class="bi bi-tools"></i> Trigger & Events reparieren / neu installieren
            </a>
            <p class="text-muted small mt-2">Hinweis: Dies stellt alle Trigger für das automatische Zählen der Runden und das 3-Minuten-Ranking-Event wieder her.</p>
        </div>
    </div>
</div>
