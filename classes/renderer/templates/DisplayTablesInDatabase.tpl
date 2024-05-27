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
                </div>
            </div>
</div>