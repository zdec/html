#!/bin/bash

# Script para levantar el entorno de desarrollo con Docker
# Ejecuta docker compose up y muestra la URL de acceso

set -e

echo ""
echo "Iniciando entorno de desarrollo..."
echo ""

# Verificar que Docker está instalado
if ! command -v docker &> /dev/null; then
    echo "Error: Docker no está instalado o no está en el PATH."
    echo "Instala Docker desde: https://docs.docker.com/get-docker/"
    exit 1
fi

if ! docker compose version &> /dev/null; then
    echo "Error: Docker Compose no está disponible."
    echo "Instala Docker Compose: https://docs.docker.com/compose/install/"
    exit 1
fi

echo "Docker detectado."
echo "URL de acceso: http://localhost:8080"
echo ""
echo "Presiona Ctrl+C para detener el entorno."
echo ""

docker compose up
