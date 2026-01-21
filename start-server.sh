#!/bin/bash

# Script para iniciar servidor local para desarrollo
# Intenta usar PHP primero (compatible con CPanel), luego Python o Node.js

echo "🚀 Iniciando servidor local..."
echo "📁 Directorio: $(pwd)"
echo "🌐 Servidor disponible en: http://localhost:8000"
echo "📝 Presiona Ctrl+C para detener el servidor"
echo ""

# Verificar qué servidor está disponible
if command -v php &> /dev/null; then
    PHP_VERSION=$(php -v | head -n 1)
    echo "✅ PHP detectado: $PHP_VERSION"
    echo "💡 Usando PHP (compatible con CPanel)"
    echo ""
    php -S localhost:8000
elif command -v python3 &> /dev/null; then
    PYTHON_VERSION=$(python3 --version)
    echo "✅ Python detectado: $PYTHON_VERSION"
    echo "💡 Usando Python (servidor alternativo)"
    echo "⚠️  Nota: Para mejor compatibilidad con CPanel, instala PHP"
    echo ""
    python3 -m http.server 8000
elif command -v node &> /dev/null; then
    NODE_VERSION=$(node --version)
    echo "✅ Node.js detectado: $NODE_VERSION"
    echo "💡 Usando Node.js (servidor alternativo)"
    echo "⚠️  Nota: Para mejor compatibilidad con CPanel, instala PHP"
    echo ""
    npx serve -p 8000
else
    echo "❌ Error: No se encontró ningún servidor disponible"
    echo ""
    echo "Por favor, instala uno de los siguientes:"
    echo "  1. PHP (recomendado para CPanel):"
    echo "     brew install php"
    echo ""
    echo "  2. Python (ya viene en macOS):"
    echo "     python3 -m http.server 8000"
    echo ""
    echo "  3. Node.js:"
    echo "     brew install node"
    echo "     npx serve -p 8000"
    exit 1
fi
