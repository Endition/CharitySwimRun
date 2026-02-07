#!/bin/bash

SCHEMA_EXISTS=$(mysql --skip-ssl-verify-server-cert  -h "$DB_SERVER" -u "$DB_BENUTZER" -p"$DB_PASSWORT" \
  -N -s -e "
    show databases; 
  " | grep auswertung)

echo $SCHEMA_EXISTS
if [ "$SCHEMA_EXISTS" = "auswertung" ]; then

cat > /var/www/html/CharitySwimRun/config/dbConfigDaten.php << EOF
<?php
\$EA_SQL = [];
\$EA_SQL["server"]='${DB_SERVER}';
\$EA_SQL["benutzer"]='${DB_BENUTZER}';
\$EA_SQL["passwort"]='${DB_PASSWORT}';
EOF

fi

exec $@
