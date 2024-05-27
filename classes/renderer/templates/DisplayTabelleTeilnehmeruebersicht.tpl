<table 	class="table table-striped table-bordered table-hover"
data-toggle="table"
data-search="true" 
data-pagination="true"
id="teilnehmeruebersicht">
    <thead>
        <tr>
            <th>Id</th>
            <th>StNr</th>
                {if $konfiguration->getTransponder() eq true}
                <th>TP</th>
                {/if}
            <th>Name</th>
            <th>Geburtsjahr</th>
            <th>Strecke</th>
            <th>AK</th>
            <th>Geschlecht</th>
            <th>Startzeit</th>
            <th>letzte Buchung</th>
            <th>Gesamtzeit</th>
            <th>Meter</th>
        </tr>
    </thead>
    <form role="form" name="FehlbuchungenForm" id="FehlbuchungenForm"
          action="{$actionurl}" method="POST">
        <tbody>
            {foreach from=$teilnehmer key=schluessel item=value}
                <tr class="odd gradeX">
                    <td>{$value->getId()}</td>
                    <td>{$value->getStartnummer()}</td>
                    {if $konfiguration->getTransponder() eq true}
                        <td>{$value->getTransponder()}</td>
                    {/if}
                    <td>{$value->getGesamtname()}</td>
                    <td>{$value->getGeburtsdatum()->format("Y")}</td>
                    <td>{$value->getStrecke()->getBezKurz()}</td>
                    <td>{$value->getAltersklasse()->getAltersklasseKurz()}</td>
                    <td>{$value->getGeschlecht()}</td>
                    <td>{$value->getStartzeit()->format("d.m.Y H:i:s")}</td>
                    <td>{$value->getletzteBuchung()}</td>
                    <td>{$value->getGesamtzeit()}</td>
                    <td>{$value->getMeter()}</td>
                </tr>
            {/foreach}
        </tbody>
    </form>
</table>