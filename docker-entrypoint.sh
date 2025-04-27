#!/bin/bash
# Démarrer le service MySQL
service mysql start

# Configurer l'utilisateur root pour qu'il puisse se connecter avec un mot de passe vide
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';"

# Créer la base de données 'event' si elle n'existe pas
mysql -e "CREATE DATABASE IF NOT EXISTS event;"

# Lancer Apache en avant-plan
exec "$@"
