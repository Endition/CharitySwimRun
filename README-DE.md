
<a href="https://github.com/Endition/CharitySwimRun/blob/master/README-DE.md">Readme in Deutsch</a> | <a href="https://github.com/Endition/CharitySwimRun/blob/master/README.md">Readme in English</a>

<h1>CharitySwimRun</h1>
24h-Schwimmen, 12h-Schwimmen, Benefiz-Schwimmen oder -Läufe können mit dieser kostenlosen OpenSource-Software abgewickelt werden. Teilnehmer verwalten, Bahnen/Runden zählen, Ergebnisse auswerten und Urkunden drucken sind nur einige der Möglichkeiten.
Gedacht ist die Software für den temporären Einsatz auf einem Webserver in einem lokalen Netzwerk. Durch die Software, findet eine entsprechende Veranstaltung praktisch papierlos statt. Das Einrichten von Xampp oder Laragon ist auf den ersten Blick komplex, muss aber nur 1x gemacht werden.
Die Software läuft im normalen Internetbrowser und ist responsive. Sie kann also von allen Geräten im Netzwerk ohne weitere Voraussetzungen benutzt werden. Danke an <a href="https://github.com/zuramai/mazer">zuramai</a> für das Dashboard.

Das Userinferface ist in deutsch. (Aus historischen Gründen ist der Code auch in deutsch. Wird stetig umgebaut)

<h2>Features</h2>
<ul>
    <li>Mannschaften und Mannschaftswertung</li>
    <li>Altersklasse und Altersklassenwertung</li>
    <li>Vereine und Vereinswertung</li>
    <li>verschiedene Strecken können gleichzeitig erfasset werden</li>
    <li>Sonderwertungen (z.B. Nachtwertung, 13:00Uhr Wertung) individuell definierbar. </li>
    <li>anpassbare PDF-Urkunden, sowie Ergebnisliste, Meldelisten</li>
    <li>Live-Anzeige von Buchungen</li>
    <li>Selbstauskunftsmöglichkeit für Teilnehmer</li>
    <li>Statistische Auswertung</li>
    <li>Alle Infos für die Presse und die Verantwortlichen auf einer Seite</li>
    <li>Verschiedenste Möglichkeiten die Buchungen zu erfassen</li>
    <li>Jede Buchung wird einzeln gespeichert. Schreiben automatische Rundenzähler (z.B. per RFID) direkt in die zugehörige DB-Tabelle, besteht Kompatibiltät zu jeder RFID-Anlage </li>
</ul>

<h2>Voraussetzungen</h2>
<ul>
    <li>Lokaler Webserver mit PHP > 8.0, Datenbank und Composer. Zum Beispiel: Laragon oder XAMPP. Bitte beachten. Beide Beispiele sind für lokales entwickeln gedacht. Sie sollen nicht ohne zusätzliche Konfiguration als Webserver im Internet genutzt werden. Gewisse Einstellungen müssen geändert werden, um den sicheren Einsatz zu ermöglichen.</li>
    <li>Laragon: https://laragon.org/</li>
    <li>Xampp: https://www.apachefriends.org/</li>
    <li>Composer: https://getcomposer.org/; </li>
    <li>GUI für Composer: https://getcomposercat.com/ </li>
    <li>Git: https://git-scm.com/downloads (wird für Composer benötigt)</li>
</ul>


<h2>Installation</h2>
<h3>Vorhandener Server</h3>
<ol>
    <li>Dateien kopieren nach CharitySwimRun/ </li>
    <li>Composer aufrufen und Abhängigkeiten installieren.</li>
    <li>Weiter mit Abschnitt "Konfiguration"</li>
</ol>

<h3>Lokaler Webserver am Beispiel von Xampp</h3>
<ol>
    <li>Xampp-Version mit PHP > 8.1 herunterladen und unter Windows möglichst nicht auf C:/ installieren</li>
    <li>Dateien aus dem Repository herunterladen (Button: <> Code -> download Zip). Zip entpacken und in den Ordner xampp/htdocs/CharitySwimRun kopieren</li>
    <li>Git, Composer und ComposerCat installieren, wenn nicht vorhanden. Git ist eine Voraussetzung für Composer. Composer wird für ComposerCat benötigt.</li>
    <li>Mit Composer(Cat) in den Ordner xmapp/htdocs/CharitySwimRun/ navigieren und "composer upate" und im Anschluss "install all" ausführen</li>
    <li>Die Datenbank mit Passwort sichern. Dazu PHPMyAdmin (127.0.0.1/phpmyadmin/) aufrufen und im Tab "User" einen neuen User mit Passwort und allen Rechten anlegen. Den vorhanden User mit dem Namen "root" im Anschluss löschen</li>
    <li>Loginmodus ("auth type") der Datenbank in xmapp/phpmyadmin/config.inc.php von "cookie" auf "http" ändern</li>
    <li>Über das XAMPP Control-Panel MariaDB/MySql neustarten</li>
</ol>

<h3>Lokaler Webserver am Beispiel von Laragon</h3>
<ol>
    <li>Laragon nicht auf C:/ (Windowspartion) installieren</li>
    <li>PHPMyAdmin hinzufügen (Quick Add -> PHPMyAdmin)</li>
    <li>PHP > 8.1 hinzufügen, wenn notwendig (<a href="https://medium.com/@oluwaseye/add-different-php-versions-to-your-laragon-installation-d2526db5c5f1">Anleitung</a>, <a href="https://windows.php.net/downloads/releases/">PHP Download</a>)</li>
    <li>
        MYSQL Einstellungen modifizieren über die Console in Laragon. Die drei Kommandos ausführen
        - mysql -u your_username -p
        - USE your_database;
        - SET GLOBAL sql_mode='';
    </li>
    <li>(Nur für Entwickler: Einstellungen für XDebug: <a href="https://gitbook.deddy.me/laragon-xdebug-debug-php-with-vscode-on-windows/">Anleitung für Laragon</a>, <a href="https://pen-y-fan.github.io/2021/08/03/How-to-Set-up-VS-Code-to-use-PHP-with-Xdebug-3-on-Windows/">Anleitung Visual Studio</a>)
 </li>
     <li>Die Datenbank mit Passwort sichern. Dazu PHPMyAdmin (127.0.0.1/phpmyadmin/) aufrufen und im Tab "User" einen neuen User mit Passwort und allen Rechten anlegen. Den vorhanden User mit dem Namen "root" im Anschluss löschen</li>
    <li>MariaDB/MySql neustarten</li>
    <li>Dateien aus dem Repository herunterladen (Button: <> Code -> download Zip). Zip entpacken und in den Ordner laragon/www/CharitySwimRun kopieren</li>
    <li>Git, Composer und ComposerCat installieren, wenn nicht vorhanden. Git ist eine Voraussetzung für Composer. Composer wird für ComposerCat benötigt.</li>
    <li>Mit Composer(Cat) in den Ordner htdocs/CharitySwimRun/ navigieren und "composer upate" und im Anschluss "install all" ausführen</li>
    <li>ChartJs via Composer kommt nicht zusammengebaut. Daher manueller Download von <a href="https://www.jsdelivr.com/package/npm/chart.js?path=dist">jsdelivr</a> erforderlich. Anschließend verschieben des /dist Ordner in den vendoer/nnnick/chartjs</li>
</ol>



<h2>Konfiguration</h2>
<ol>
    <li>Software aufrufen: 127.0.0.1/CharitySwimRun/</li>
    <li>Datenbankverbindungsdaten eingeben, wie im Schritt "Lokaler Webserver einrichten" angelegt. Datenbank und Tabellen werden automatisch erstellt. Werden die Daten nicht gespeichert, weil die Datei CharitySwimRun/config/dbConfigDaten.php nicht geschrieben wird, liegt das an der Installation auf C:/. Dann die Datei einfach manuell bearbeiten und die Daten manuell in die Felder eintragen.</li>
    <li>Einstellungen setzen</li>
    <li>Strecken anlegen</li>
    <li>Altersklassen anlegen</li>
    <li>(Nutzer anlegen)</li>
    <li>"Simulator" ausprobieren.</li>
</ol>

<h2>Möglichkeiten Buchungen zu registrieren am Beispiel Schwimmveranstaltung</h2>
<h3>Am Beckenrand</h3>
Am Beckenrand sitzen menschliche Bahnenzähler. Diese haben ein Tablet, welches per lokalem Netzwerk dem Server verbunden ist. Über "Verwaltung" -> manuelle Eingaben (unterer Bereich) bauen Sie sich ein individuelles Set der Schwimmer auf ihrer Bahn und buchen die Bahnen per Klick.

<h3>Bei "Abmeldung" der Teilnehmer</h3>
Die Teilnehmer bekommen z.B. für jede Runde einen Gummiring. Die Gummieringe werden regelmäßig an einer zentralen Stelle abgegeben und über "Verwaltung" -> manuelle Eingaben (oberer Bereich) gebucht.

<h3>Mittels RFID Anlage</h3>
Eine RFID Anlage mit einer Software ist mit der der Datenbank verbunden und schreibt ihre Buchungen in die DB. Dazu bitte Kontakt mit dem Admin aufnehmen.