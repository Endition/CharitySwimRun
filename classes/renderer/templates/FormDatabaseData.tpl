<div class="card">
			<h5 class="card-header">Datenbankverbindung anlegen</h5>
			<div class="card-body">
			<form role="form" name="DatabaseForm" id="DatabaseForm" action="{$actionurl}" method="POST">
			{include file='templateInputElement.tpl' bezeichnung='Server (localhost oder IP)' name='server' type='text' value=$EA_Repository->getServer() required=true }
			{include file='templateInputElement.tpl' bezeichnung='Benutzer' name='benutzer' type='text' value=$EA_Repository->getUser() required=true }
			{include file='templateInputElement.tpl' bezeichnung='Passwort' name='passwort' type='password' value=$EA_Repository->getPassword() required=false }
			{include file='templateInputElement.tpl' name='sendDatabaseData' type='submit'}
			</form>
</div></div>