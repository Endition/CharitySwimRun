
<a href="https://github.com/Endition/CharitySwimRun/blob/master/README-DE.md">Readme in Deutsch</a> | <a href="https://github.com/Endition/CharitySwimRun/blob/master/README.md">Readme in English</a>

<h1>CharitySwimRun</h1>
24h-Schwimmen, 12h-Schwimmen, Benefiz-Schwimmen oder -Läufe abwickeln. Teilnehmer verwalten, Bahnen/Runden zählen, Ergebnisse auswerten und Urkunden drucken.
Gedacht ist die Software für den temporären Einsatz auf einem Webserver in einem lokalen Netzwerk. 
Die Software läuft im Browser und ist responsive. Sie kann also von allen Geräten im Netzwerk ohne weitere Voraussetzungen benutzt werden. Danke an <a href="https://github.com/zuramai/mazer">zuramai</a> für das Dashboard.

Das Userinferface ist in deutsch. (Aus historischen Gründen ist der Code auch in deutsch. Wird stetig umgebaut)

<h2>Features</h2>
<ul>
    <li>Altersklasse</li>
    <li>Strecken</li>
    <li>Sonderwertungen</li>
    <li>Mannschaften</li>
    <li>anpassbare PDF-Urkunden, sowie Ergebnisliste, Meldelisten</li>
    <li>Live-Anzeige von Buchungen</li>
    <li>Selbstauskunftsmöglichkeit für Teilnehmer</li>
    <li>Statistische Auswertung</li>
    <li>Jede Buchung wird einzeln gespeichert. Schreiben automatische Rundenzähler (z.B. per RFID) direkt in die zugehörige DB-Tabelle, besteht Kompatibiltät zu jeder RFID-Anlage </li>
</ul>

<h2>Voraussetzungen</h2>
<ul>
    <li>Lokaler Webserver mit PHP > 8.1, Datenbank und  Composer. Zum Beispiel: Laragon oder XAMPP. Bitte beachten. Beide Beispiele sind für lokales entwickeln gedacht. Sie sollen nicht ohne zusätzliche Konfiguration als Webser genutzt werden. Gewisse Einstellungen müssen geändert werden, um den sicheren Einsatz zu ermöglichen.</li>
    <li>Laragon: https://laragon.org/</li>
    <li>Xampp: https://www.apachefriends.org/</li>
    <li>Composer: https://getcomposer.org/; </li>
    <li>Composer with GUI: https://getcomposercat.com/ </li>
</ul>


<h2>Installation</h2>
<h3>Vorhandener Server</h3>
<ol>
    <li>Dateien kopieren nach CharitySwimRun/ </li>
    <li>Composer aufrufen und Abhänigkeiten installieren. </li>
    <li>Weiter mit Abschnitt "Konfiguration"</li>
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
    <li>Datenbank mit Passwort sichern. Dazu PHPMyAdmin aufrufen und einen neuen User mit Passwort und allen Rechten anlegen. Root User im Anschluss löschen</li>
    <li>MariaDB/MySql neustarten</li>
    <li>Die Dateien in den Ordner www/ speichert -> Ziel: www/CharitySwimRun/</li>
    <li>Composer installieren, wenn nicht vorhanden</li>
    <li>Mit Composer in den Ordner www/CharitySwimRun/ navigieren und "install all" ausführen</li>
    <li>ChartJs via Composer kommt nicht zusammengebaut. Daher manueller Download von <a href="https://www.jsdelivr.com/package/npm/chart.js?path=dist">jsdelivr</a> erforderlich. Anschließend verschieben des /dist Ordner in den vendoer/nnnick/chartjs</li>
</ol>

<h3>Lokaler Webserver am Beispiel von Xampp</h3>
<ol>
    <li>Xampp (PHP > 8.1) nicht auf C:/ installieren</li>
    <li>Dateien in xampp/htdocs/CharitySwimRun kopieren</li>
    <li>Mit Composer in den Ordner htdocs/CharitySwimRun/ navigieren und "install all" ausführen</li>
    <li>Datenbank mit Passwort sichern. Dazu PHPMyAdmin aufrufen und einen neuen User mit Passwort und allen Rechten anlegen. Root User im Anschluss löschen</li>
    <li>MariaDB/MySql neustarten</li>
    <li>Loginmodus (auth type) der Datenbank in phpmyadmin/config.inc.php auf http ändern</li>
</ol>

<h2>Konfiguration</h2>
<ol>
    <li>Software aufrufen: localhost/CharitySwimRun/</li>
    <li>Datenbankverbindungsdaten eingeben. Datenbank und Tabellen werden automatisch erstellt. Werden die Daten nicht gespeichert, weil die ConfigDatei nicht geschrieben wird, liegt das an der Installation auf C:/. Dazu probieren den Server zum Anlegen der Datenbankverbindungsdaten als Administrator laufen zu lassen. Zweite Möglichkeit: Datei manuell anlegen.</li>
    <li>Einstellungen machen</li>
    <li>Strecken anlegen</li>
    <li>Altersklassen anlegen</li>
    <li>Nutzer anlegen</li>
    <li>"Simulator" ausprobieren.</li>
</ol>

<h2>Möglichkeiten Buchungen zu registrieren am Beispiel Schwimmveranstaltung</h2>
<h3>Am Beckenrand</h3>
Am Beckenrand sitzen Bahnenzähler. Diese haben ein Tablet, was mit der Software verbunden ist. Über "Eingabe und Verwaltung" -> manuelle Eingaben bauen Sie sich ein individuelles Set "Ihrer" Schwimmer und buchen die Bahnen per Klicke.

<h3>Bei "Abmeldung" der Teilnehmer</h3>
Die Teilnehmer bekommen z.B. für jede Runde einen Gummiring. Die Gummieringe werden regelmäßig an einer zentralen Stelle abgegeben und über "Eingabe und Verwaltung" -> manuelle Eingaben gebucht.

<h3>Mittels RFID Anlage</h3>
Eine RFID Anlage mit einer Software ist mit der der Datenbank verbunden und schreibt ihre Buchungen in die DB. Dazu bitte Kontakt mit dem Admin aufnehmen.