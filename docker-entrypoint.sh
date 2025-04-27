#!/bin/bash
# Démarrer MySQL installé dans le conteneur (via distribution Debian slim)
service mysql start 2>/dev/null || true

# Créer la base event si nécessaire
mysql -u root -e "CREATE DATABASE IF NOT EXISTS event;" 2>/dev/null || true

# Lancer Apache
exec "$@"
