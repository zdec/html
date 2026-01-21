@echo off
REM Script para iniciar servidor PHP local para desarrollo (Windows)
REM Compatible con CPanel (PHP 8.1)

echo.
echo 🚀 Iniciando servidor PHP local...
echo 📁 Directorio: %CD%
echo 🌐 Servidor disponible en: http://localhost:8000
echo 📝 Presiona Ctrl+C para detener el servidor
echo.

REM Verificar si PHP está instalado
php -v >nul 2>&1
if errorlevel 1 (
    echo ❌ Error: PHP no está instalado
    echo Por favor, instala PHP para continuar
    pause
    exit /b 1
)

REM Mostrar versión de PHP
echo ✅ PHP detectado:
php -v | findstr /C:"PHP"
echo.

REM Iniciar servidor PHP en el puerto 8000
php -S localhost:8000
