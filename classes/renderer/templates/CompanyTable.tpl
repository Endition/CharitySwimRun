        <div class="card">
            <h5 class="card-header">angelegte Unternehmen</h5>
            <div class="card-body">
                <table 	class="table table-striped table-bordered table-hover"
                data-toggle="table"
                data-sort-name="verein"
                data-sort-order="asc"
                data-pagination="true"
                  data-search="true">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th data-field="verein" data-sortable="true">Unternehmen</th>
                            <th data-field="mitglieder" data-sortable="true">Mitglieder</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$unternehmen item=value}
                            <tr>
                                <td>{$value->getId()}</td>
                                <td>{$value->getUnternehmen()}</td>
                                <td>{$value->getMitgliederList()->count()}</td>
                                <td><a title="beabeiten"
                                       href='{$link}&action=edit&amp;id={$value->getId()}'><i class="fa-solid fa-pen-to-square"></i></a></td>
                                <td><a title="entfernen"
                                       href='{$link}&action=delete&amp;id={$value->getId()}'><i class="fa-solid fa-trash"></i></a></td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
