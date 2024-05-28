
<a href="https://github.com/Endition/CharitySwimRun/blob/master/README-DE.md">Readme in Deutsch</a> | <a href="https://github.com/Endition/CharitySwimRun/blob/master/README.md">Readme in English</a>
<h1>CharitySwimRun</h1>
Manage 24-hour swimming, 12-hour swimming, charity swimming, or running events. Handle participants, count laps/rounds, evaluate results, and print certificates. The software is intended for temporary use on a web server in a local network. It runs in the browser and is responsive, allowing it to be used from any device in the network without additional requirements. Thanks to <a href="https://github.com/zuramai/mazer">zuramai</a> for the dashboard.

The user interface is in German. (For historical reasons, the code is also in German. It is continuously being refactored.)

<h2>Features</h2>
<ul>
    <li>Teams</li>
    <li>Age groups</li>
    <li>Routes</li>
    <li>Clubs</li>
    <li>Special ratings</li>
    <li>Customizable PDF certificates, results lists, and registration lists</li>
    <li>Live display of bookings</li>
    <li>Self-reporting capability for participants</li>
    <li>Statistical analysis</li>
    <li>All information for the press on one page</li>
    <li>Various ways to record bookings</li>
    <li>Each booking is saved individually. Automatic lap counters (e.g., via RFID) write directly to the relevant DB table, ensuring compatibility with any RFID system</li>
</ul>

<h2>Requirements</h2>
<ul>
    <li>Local web server with PHP > 8.1, database, and Composer. Examples: Laragon or XAMPP. Please note: Both examples are intended for local development. They should not be used as web servers without additional configuration. Certain settings need to be changed to ensure secure use.</li>
    <li>Laragon: <a href="https://laragon.org/">https://laragon.org/</a></li>
    <li>Xampp: <a href="https://www.apachefriends.org/">https://www.apachefriends.org/</a></li>
    <li>Composer: <a href="https://getcomposer.org/">https://getcomposer.org/</a></li>
    <li>Composer with GUI: <a href="https://getcomposercat.com/">https://getcomposercat.com/</a></li>
</ul>

<h2>Installation</h2>
<h3>Existing Server</h3>
<ol>
    <li>Copy files to CharitySwimRun/</li>
    <li>Run Composer and install dependencies.</li>
    <li>Continue with the "Configuration" section</li>
</ol>

<h3>Local Web Server Example with Laragon</h3>
<ol>
    <li>Do not install Laragon on C:/ (Windows partition)</li>
    <li>Add PHPMyAdmin (Quick Add -> PHPMyAdmin)</li>
    <li>Add PHP > 8.1 if necessary (<a href="https://medium.com/@oluwaseye/add-different-php-versions-to-your-laragon-installation-d2526db5c5f1">Guide</a>, <a href="https://windows.php.net/downloads/releases/">PHP Download</a>)</li>
    <li>
        Modify MYSQL settings via the console in Laragon. Execute the three commands:
        <ul>
            <li>mysql -u your_username -p</li>
            <li>USE your_database;</li>
            <li>SET GLOBAL sql_mode='';</li>
        </ul>
    </li>
    <li>(For developers only: XDebug settings: <a href="https://gitbook.deddy.me/laragon-xdebug-debug-php-with-vscode-on-windows/">Laragon Guide</a>, <a href="https://pen-y-fan.github.io/2021/08/03/How-to-Set-up-VS-Code-to-use-PHP-with-Xdebug-3-on-Windows/">Visual Studio Guide</a>)</li>
    <li>Secure the database with a password. Open PHPMyAdmin and create a new user with a password and all rights. Then delete the root user.</li>
    <li>Restart MariaDB/MySQL</li>
    <li>Save the files to the www/ directory -> target: www/CharitySwimRun/</li>
    <li>Install Composer if not already installed</li>
    <li>Navigate to the www/CharitySwimRun/ directory with Composer and run "install all"</li>
    <li>ChartJs via Composer is not bundled. Download manually from <a href="https://www.jsdelivr.com/package/npm/chart.js?path=dist">jsdelivr</a> and move the /dist folder to vendor/nnnick/chartjs</li>
</ol>

<h3>Local Web Server Example with Xampp</h3>
<ol>
    <li>Do not install Xampp (PHP > 8.1) on C:/</li>
    <li>Copy files to xampp/htdocs/CharitySwimRun</li>
    <li>Navigate to the htdocs/CharitySwimRun/ directory with Composer and run "install all"</li>
    <li>Secure the database with a password. Open PHPMyAdmin and create a new user with a password and all rights. Then delete the root user.</li>
    <li>Restart MariaDB/MySQL</li>
    <li>Change the database login mode (auth type) in phpmyadmin/config.inc.php to http</li>
</ol>

<h2>Configuration</h2>
<ol>
    <li>Open the software: localhost/CharitySwimRun/</li>
    <li>Enter the database connection details. The database and tables are created automatically. If the data is not saved because the config/dbConfigDaten.php file cannot be written, it is due to the installation on C:/. In this case, edit the file manually and enter the data.</li>
    <li>Set the settings</li>
    <li>Create routes</li>
    <li>Create age groups</li>
    <li>(Create users)</li>
    <li>Try the "Simulator"</li>
</ol>

<h2>Ways to Register Bookings for Swimming Events</h2>
<h3>At the Poolside</h3>
At the poolside, human lap counters are seated with a tablet connected to the software. Through "Input and Management" -> manual entries (lower section), they build a custom set of swimmers on their lane and book the laps by clicking.

<h3>When Participants "Check Out"</h3>
Participants receive a rubber ring for each lap. The rubber rings are regularly submitted at a central location and booked through "Input and Management" -> manual entries (upper section).

<h3>Using RFID System</h3>
An RFID system with software is connected to the database and writes its bookings to the DB. Please contact the admin for more details.
