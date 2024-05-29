    <div class="row">
            <div class="card">
                <h5 class="card-header">
                    Angelegte Datenbanktabellen
                </h5>
                <div class="card-body">
                    <ul>
                        {foreach from=$tabellen key=schluessel item=value}
                            <li>{$value->getName() } 
                        {/foreach}
                    </ul>
                    <form method="post">
                    <input class="btn btn-danger" formaction="{$actionurl}&action=deleteDatabase" type="submit" name="resetDatabase" value="Datenbank resetten (Das löscht ALLE Daten!)" onClick="return confirm('Soll wirklich gelöscht werden?')" />
                    </form>
                </div>
            </div>
</div>