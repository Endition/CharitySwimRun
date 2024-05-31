<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">angelegte Sonderauswertungen</h5>
            <div class="card-body">
                <table 	class="table table-striped table-bordered table-hover"
                data-toggle="table"
                  data-search="true"
                  data-pagination="true">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Start</th>
                            <th>Ende</th>
                            <th>Strecke</th>
                            <th>Altersklasse</th>
                            <th>Geschlecht</th>
                            <th>Leser</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$specialEvaluationList item=specialEvaluation}
                            <tr>
                                <td>{$specialEvaluation->getId()}</td>
                                <td>{$specialEvaluation->getName()}</td>
                                <td>{$specialEvaluation->getStart()->format("d.m.Y H:i:s")}</td>
                                <td>{$specialEvaluation->getEnd()->format("d.m.Y H:i:s")}</td>
                                <td>{$specialEvaluation->getStrecke()}</td>
                                <td>{$specialEvaluation->getAltersklasse()}</td>
                                <td>{$specialEvaluation->getGeschlecht()}</td>
                                <td>{$specialEvaluation->getLeser()}</td>
                                <td><a title="beabeiten"
                                       href='{$link}&action=edit&amp;id={$specialEvaluation->getId()}'><i class="fa-solid fa-pen-to-square"></i></a></td>
                                <td><a title="entfernen"
                                       href='{$link}&action=delete&amp;id={$specialEvaluation->getId()}'><i class="fa-solid fa-trash"></i></a></td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>