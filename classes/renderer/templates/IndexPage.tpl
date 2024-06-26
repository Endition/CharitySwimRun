	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<h5 class="card-header">Aufruf von anderen Geräten</h5>
				<div class="card-body">
					<p>Um das Programm von einem anderen Rechner aus im Netzwerk aufzurufen, folgende Adressen
						(zuerst die im Bereich 130 - 192) in den Browser eingeben. Wenn hier mehrere stehen, sollte eine
						gehen:</p>
					<ul>
						{foreach $ipList as $ip}
							<li> {$ip}</li>
						{/foreach}
					</ul>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card">
				<h5 class="card-header">Links</h5>
				<div class="card-body">
					<ol>
						<li><a target="_blank" href="https://github.com/Endition/CharitySwimRun">Link zu Github</a></li>
						<li><a target="_blank" href="{$httphost}/phpmyadmin/">Link zu PHPMyAdmin</a></li>
					</ol>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="card">
				<h5 class="card-header">Zeit</h5>
				<div class="card-body">
					<ol>
						<li>Serverzeit: {$serverzeit}</li>
						<li>Serverzeitzone: {$serverzeitzone}</li>
						<li>Änderung in php.ini: date.timezone = Europe/Berlin</li>
					</ol>
				</div>
			</div>
		</div>
	</div>	