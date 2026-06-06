	<div class="row">
		<div class="col-lg-6">
			<div class="card">
				<h5 class="card-header">Aufruf von anderen Geräten (z.B. Smartphone)</h5>
				<div class="card-body">
					<p>Um das Programm von einem anderen Gerät im Netzwerk aufzurufen, wird die lokale IP-Adresse dieses Computers benötigt.</p>
					<p>Da das System in Docker läuft, wird hier aktuell meist nur die interne Docker-IP (z.B. 172.18.x.x) angezeigt. Diese funktioniert auf dem Smartphone <strong>nicht</strong>.</p>
					<p><strong>So ermitteln Sie Ihre korrekte IP-Adresse:</strong></p>
					<ol>
						<li>Öffnen Sie unter Windows die Eingabeaufforderung (cmd).</li>
						<li>Tippen Sie <code>ipconfig</code> ein und drücken Sie Enter.</li>
						<li>Suchen Sie nach Ihrer <strong>IPv4-Adresse</strong> (meist im Bereich 192.168.x.x).</li>
						<li>Geben Sie diese IP im Smartphone-Browser ein, gefolgt von Port und Pfad (z.B. <code>http://192.168.x.x:8080/CharitySwimRun/</code>).</li>
					</ol>
					<p>Zusätzlich erkannte (interne) Adressen:</p>
					<ul>
						{foreach $ipList as $ip}
							<li> {$ip}</li>
						{/foreach}
					</ul>
					<hr>
					<h6>Windows Firewall Freigaben</h6>
					<p>Sollte die Anwendung von anderen Geräten im Netzwerk nicht erreichbar sein, blockiert vermutlich die Windows-Firewall den eingehenden Datenverkehr. Für die Weboberfläche wird Port <strong>8080</strong> benötigt, für die direkte Datenbankverbindung der RFID-Anlage Port <strong>3306</strong>.</p>
					<p><strong>Lösung:</strong> Führen Sie diese Befehle in einer als Administrator gestarteten PowerShell aus, um beide Ports freizugeben:</p>
					<code class='d-block bg-dark text-white p-2 mt-2 rounded'>New-NetFirewallRule -DisplayName "Docker Web Port 8080" -Direction Inbound -LocalPort 8080 -Protocol TCP -Action Allow<br>New-NetFirewallRule -DisplayName "Docker MySQL Port 3306" -Direction Inbound -LocalPort 3306 -Protocol TCP -Action Allow</code>
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