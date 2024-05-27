<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <h5 class="card-header">angelegte User</h5>
            <div class="card-body">
                <table 	class="table table-striped table-bordered table-hover"
                data-toggle="table"
                  data-search="true"
                  data-pagination="true">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Username</th>
                            <th>Rolle</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$userList key=schluessel item=value}
                            <tr>
                                <td>{$value->getId()}</td>
                                <td>{$value->getUsername()}</td>
                                <td>{$value->getUserroleBezeichnung()}</td>
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
    </div>
</div>