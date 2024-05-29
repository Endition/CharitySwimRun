		<div class="card">
			<h5 class="card-header">Urkundenelement anlegen</h5>
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form role="form" name="UrkundenelementForm" id="UrkundenelementForm"
							action="{$actionurl}" method="POST" class="form-horizontal">

							{include file='templateInputElement.tpl' name='id' type='hidden' value=$urkundenelement->getId() required=true}
                            {include file='templateInputElement.tpl' name='x_wert' type='number' value=$urkundenelement->getX_wert() required=true bezeichnung='X-Wert' min=1 max=595 step=1}
                            {include file='templateInputElement.tpl' name='x_wert' type='number' value=$urkundenelement->getY_wert() required=true bezeichnung='Y-Wert' min=1 max=842 step=1}
                            {include file='templateInputElement.tpl' name='breite' type='number' value=$urkundenelement->getBreite() required=true bezeichnung='Breite'}
                            {include file='templateInputElement.tpl' name='hoehe' type='number' value=$urkundenelement->getHoehe() required=true bezeichnung='Höhe'}
							{include file='templateInputElement.tpl' name='inhalt' type='select' selectedElement=$urkundenelement->getInhalt() required=true bezeichnung='Inhalt' selectValueList=$inhalt_selectvalues }
							{include file='templateInputElement.tpl' name='freitext' type='text' value=$urkundenelement->getFreitext() required=true bezeichnung='Freitext'}
							{include file='templateInputElement.tpl' name='schriftart' type='select' selectedElement=$urkundenelement->getSchriftart() required=true bezeichnung='Schriftart' selectValueList=$schriftart_selectvalues  }
							{include file='templateInputElement.tpl' name='schriftgroesse' type='number' value=$urkundenelement->getSchriftgroesse() required=true bezeichnung='Schriftgröße'}
							{include file='templateInputElement.tpl' name='schrifttyp' type='select' selectedElement=$urkundenelement->getSchrifttyp() required=true bezeichnung='Schrifttyp' selectValueList=$schrifttyp_selectvalues  }
							{include file='templateInputElement.tpl' name='ausrichtung' type='select' selectedElement=$urkundenelement->getAusrichtung() required=true bezeichnung='Ausrichtung' selectValueList=$ausrichtung_selectvalues  }
							{include file='templateInputElement.tpl' name='sendUrkundenelementData' type='submit'}

						</form>
					</div>
				</div>
			</div>
		</div>
