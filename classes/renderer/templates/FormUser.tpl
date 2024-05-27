<div class="row">
	<div class="col-lg-12">
		<div class="card">
			<h5 class="card-header">User anlegen</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form role="form" name="UserForm" id="UserForm"
							action="{$actionurl}" method="POST" class="form-horizontal">
							{include file='templateInputElement.tpl' name='id' type='hidden' value=$user->getId() required=true}
							{include file='templateInputElement.tpl' name='username' type='text' value=$user->getUsername() required=true bezeichnung='Username'}
							{include file='templateInputElement.tpl' name='password' type='password' required=true bezeichnung='Passwort' min=8}
							{include file='templateInputElement.tpl' name='password2' type='password'  required=true bezeichnung='Passwort wiederholen' min=8}
							{include file='templateInputElement.tpl' name='userroleId' type='select' selectedElement=$user->getUserroleId() required=true bezeichnung='Rolle' selectValueList=$user->getUserroleList() emptyElement=true  }
							{include file='templateInputElement.tpl' name='sendUserData' type='submit'}
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
