<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">Strecken/AK Matrix (nicht besetzte AKs sind ausgeblendet)</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table width="100%"
                               class="table table-striped table-bordered table-hover"
                               id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>AK/Strecke</th>
                                        {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                        <th colspan="4">{$value['Bezeichnung']}</th>
                                        {/foreach}	

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><b>ohne AK-Filter</b></td>
                                    {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                        <td><a href="{$pdfurl}&format=pdf&typ=Einzelstarter&Strecke={$schluessel}&Geschlecht=M">M</a></td>
                                        <td><a href="{$pdfurl}&format=pdf&typ=Einzelstarter&Strecke={$schluessel}&Geschlecht=W">W</a></td>
                                        <td><a href="{$pdfurl}&format=pdf&typ=Einzelstarter&Strecke={$schluessel}&Geschlecht=D">D</a></td>
                                        <td><a href="{$pdfurl}&format=pdf&typ=Einzelstarter&Strecke={$schluessel}">(gesamt)</a></td>
                                    {/foreach}	
                                </tr>

                                {foreach from=$altersklassen item=akvalue}
                                    {assign var=i 1 } 
                                    {foreach from=$StreckenAltersklassenTeilnehmerVerteilung key=schluessel item=value} 
                                        {if isset($AltersklassenTeilnehmerVerteilung[{$akvalue->getId()}]["Anzahl"])}  
                                            {if $i == 1}
                                                <tr><td>{$akvalue->getAltersklasse()}</td>
                                                {/if}
                                                {if isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung']["M"]) || isset($value['Unterteilung'][{$akvalue->getId()}]['Unterteilung']["W"])}
                                                    {foreach from=$geschlechter key=geschlechterkey item=geschlechtervalue}
                                                            {if isset($value['Unterteilung'][{$akvalue->getId()}]["Unterteilung"][{$geschlechtervalue}])} 
                                                        <td><a href="{$pdfurl}&format=pdf&typ=Einzelstarter&Altersklasse={$akvalue->getId()}&Strecke={$schluessel}&Geschlecht={$geschlechtervalue}">{$geschlechtervalue}</a></td>	
                                                         {else}
                                                         <td>-</td>
                                                            {/if}
                                                    {/foreach}
                                                    <td><a href="{$pdfurl}&format=pdf&typ=Einzelstarter&Altersklasse={$akvalue->getId()}&Strecke={$schluessel}">(gesamt)</a></td>
                                                {else}
                                                    <td></td><td></td> <td></td>   <td></td> 
                                                {/if}
                                                {if $i == count($StreckenAltersklassenTeilnehmerVerteilung)}
                                                </tr>
                                            {/if}
                                        {/if}
                                        {assign var=i $i+1 }  
                                    {/foreach}										
                                    </tr>	
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
     
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Startgruppen</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <ul>
                            {if is_array($startgruppen) == true}
                                {foreach from=$startgruppen key=schluessel item=value} 
                                    <li><a href="{$pdfurl}&format=pdf&typ=Einzelstarter&Startgruppe={$value}">{$value}</a></li>
                                    {/foreach}
                                {else}
                                <li>Keine Startgruppen angelegt</li>
                                {/if}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <h5 class="card-header">Sonderdokumente</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <ul>
                            <li><a href="{$pdfurl}&format=pdf&typ=Einzelstarter">Alle Einzelstarter</a></li>
                            <li><a href="{$pdfurl}&format=pdf&typ=Einzelstarter&alle=streckenxaltersklassen">Alle Strecken/Altersklassen</a></li>
                                {foreach from=$strecken key=schluessel item=value} 
                                <li><a href="{$pdfurl}&format=pdf&typ=Mannschaften&Strecke={$schluessel}">Mannschaften ({$value->getBezKurz()})</a></li>
                                <li><a href="{$pdfurl}&format=pdf&typ=Vereine&Strecke={$schluessel}">Vereine ({$value->getBezKurz()})</a></li>
                                {/foreach}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
