
<div class="card">
<h5 class="card-header">Statistik: {$title} </h5>
    <div class="card-body">
    <p>{$explanation}</p>
                {if $daten === []}
                    Noch keine Daten vorhanden. Wird hier trotz Daten nichts angezeigt, muss das PlugIn JSChart manuell herunter geladen werden (/dist Folder) und nach /verndor kopiert werden.
                {else}
                    <div><canvas id="showChart"></canvas></div>
                {/if}
    </div>
</div>


<script>
let dataObjectList = {json_encode($daten)};
let streckenart = "{$konfiguration->getStreckenart()}";
</script>

{literal}
<script>
(async function() {
    new Chart(
        document.getElementById('showChart'),
        {
        type: 'line',
        data: dataObjectList
        }
    );
})();
</script>
{/literal}
