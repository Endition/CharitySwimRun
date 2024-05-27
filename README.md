
<a href="https://github.com/Endition/CharitySwimRun/blob/main/README-DE.md">Readme in Deutsch</a> | <a href="https://github.com/Endition/CharitySwimRun/blob/main/README.md">Readme in English</a>

<h1>CharitySwimRun</h1>
Organize 24-hour/charity swims and runs. Manage participants, count lanes/laps, evaluate results and print certificates.
The software is intended for temporary use on a web server in a local network. 
The software runs in the browser and is responsive, meaning it can be used by all devices in the network without additional requirements.

The user interface is in German. (For historical reasions the code is also in German. I will change that continuously)

<h2>Features</h2>
<ul>
    <li>Age groups</li>
    <li>Distances</li>
    <li>Special evaluations</li>
    <li>Teams</li>
    <li>Customizable PDF certificates, as well as result lists, registration lists</li>
    <li>Live display of bookings</li>
    <li>Self-reporting option for participants</li>
    <li>Statistical evaluation</li>
    <li>Each booking is saved individually. If automatic lap counters (e.g., via RFID) write directly to the corresponding database table, compatibility with any RFID system is ensured.</li>
</ul>

<h2>Requirements</h2>
<ul>
    <li>Local web server with PHP > 8.1 with database and Composer. For example, Laragon or XAMPP. Note: Both packages are intended for local development, not for production use. They must be configured securely accordingly.
</li>
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

<h3>New Web Server Example with Laragon</h3>
<ol>
    <li>Do not install Laragon on C:/ (Windows partition)</li>
    <li>Add PHPMyAdmin (Quick Add -> PHPMyAdmin)</li>
    <li>Add PHP > 8.1 if necessary (<a href="https://medium.com/@oluwaseye/add-different-php-versions-to-your-laragon-installation-d2526db5c5f1">Instructions</a>, <a href="https://windows.php.net/downloads/releases/">PHP Download</a>)</li>
    <li>
        Modify MYSQL settings via the console in Laragon. Execute the following three commands:
        <ul>
            <li>mysql -u your_username -p</li>
            <li>USE your_database;</li>
            <li>SET GLOBAL sql_mode='';</li>
        </ul>
    </li>
    <li>(For developers only: XDebug settings: <a href="https://gitbook.deddy.me/laragon-xdebug-debug-php-with-vscode-on-windows/">Instructions for Laragon</a>, <a href="https://pen-y-fan.github.io/2021/08/03/How-to-Set-up-VS-Code-to-use-PHP-with-Xdebug-3-on-Windows/">Instructions for Visual Studio</a>)
    </li>
    <li>Secure the database with a password. To do this, open PHPMyAdmin and create a new user with a password and all rights. Then delete the root user.</li>
    <li>Restart MariaDB/MySql</li>
    <li>Save the files in the www/ directory -> Target: www/CharitySwimRun/</li>
    <li>Install Composer if not already installed</li>
    <li>Navigate to the www/CharitySwimRun/ directory with Composer and run "install all"</li>
    <li>ChartJs via Composer does not come pre-built. Therefore, a manual download from <a href="https://www.jsdelivr.com/package/npm/chart.js?path=dist">jsdelivr</a> is required. Then move the /dist folder to vendor/nnnick/chartjs</li>
</ol>

<h2>Configuration</h2>
<ol>
    <li>Access the software: localhost/CharitySwimRun/</li>
    <li>Enter the database connection details. The database and tables will be created automatically. If the data is not saved because the config file cannot be written, it is due to the installation on C:/. In this case, try running the server as an administrator to create the database connection details. Alternatively, create the file manually.</li>
    <li>Make settings</li>
    <li>Create distances</li>
    <li>Create age groups</li>
    <li>Create users</li>
    <li>Try the "Simulator"</li>
</ol>