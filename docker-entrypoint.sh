#!/bin/bash
# Démarrer le service MySQL
service mysql start

# Créer la base de données 'event' si elle n'existe pas déjà
mysql -e "CREATE DATABASE IF NOT EXISTS event;"

# Exécuter la commande par défaut (lancer Apache en mode premier plan)
exec "$@"
