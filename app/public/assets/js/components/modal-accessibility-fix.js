/**
 * Modal Accessibility Fix
 * Soluciona el problema de aria-hidden cuando un elemento dentro del modal
 * mantiene el foco al cerrar el modal.
 * 
 * Usa el atributo 'inert' (recomendado) con fallback a blur() para compatibilidad
 */

(function() {
    'use strict';

    /**
     * Verifica si el navegador soporta el atributo inert
     */
    function supportsInert() {
        return 'inert' in document.createElement('div');
    }

    /**
     * Maneja el cierre del modal con la mejor solución disponible
     */
    function handleModalHide(event) {
        const modal = event.target;
        
        if (supportsInert()) {
            // Opción 2: Usar atributo inert (recomendado)
            // El atributo inert se aplica automáticamente cuando el modal se oculta
            // pero lo hacemos explícito para asegurar que funcione correctamente
            modal.inert = true;
        } else {
            // Opción 1: Fallback - Remover el foco del elemento activo
            if (document.activeElement && modal.contains(document.activeElement)) {
                document.activeElement.blur();
            }
        }
    }

    /**
     * Maneja la apertura del modal
     */
    function handleModalShow(event) {
        const modal = event.target;
        
        if (supportsInert()) {
            // Remover inert cuando el modal se muestra
            modal.inert = false;
        }
    }

    /**
     * Inicializar el fix cuando el DOM esté listo
     */
    function initModalAccessibilityFix() {
        // Esperar a que jQuery y Bootstrap estén disponibles
        if (typeof jQuery === 'undefined' || typeof bootstrap === 'undefined') {
            // Si no están disponibles, intentar de nuevo después de un breve delay
            setTimeout(initModalAccessibilityFix, 100);
            return;
        }

        // Agregar listeners a todos los modales usando eventos de Bootstrap
        jQuery(document).on('hide.bs.modal', '.modal', handleModalHide);
        jQuery(document).on('show.bs.modal', '.modal', handleModalShow);
        jQuery(document).on('hidden.bs.modal', '.modal', function(event) {
            const modal = event.target;
            // Asegurar que inert esté aplicado después de que el modal esté completamente oculto
            if (supportsInert() && !modal.classList.contains('show')) {
                modal.inert = true;
            }
        });

        // También aplicar inert inicialmente a todos los modales que no estén visibles
        if (supportsInert()) {
            jQuery('.modal').each(function() {
                if (!jQuery(this).hasClass('show')) {
                    this.inert = true;
                }
            });
        }
    }

    // Inicializar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initModalAccessibilityFix);
    } else {
        initModalAccessibilityFix();
    }

    // También inicializar cuando los componentes se hayan cargado
    if (typeof jQuery !== 'undefined') {
        jQuery(document).on('componentsLoaded', initModalAccessibilityFix);
    }
})();
