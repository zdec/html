/**
 * Chatbot Orchestrator
 * Detecta likes, espera 20-30s y acompaña al cliente.
 */
(function () {
    const CHAT_DELAY_MIN = 20000;
    const CHAT_DELAY_MAX = 30000;
    let pendingTimer = null;
    let currentSessionId = null;
    let chatInitialized = false;
    let hasWelcomedForCurrentPrompt = false;
    let awaitingEmailForOrder = false;

    function randomDelay() {
        return Math.floor(Math.random() * (CHAT_DELAY_MAX - CHAT_DELAY_MIN + 1)) + CHAT_DELAY_MIN;
    }

    function ensureChatUI() {
        if (chatInitialized) return;
        const panel = document.createElement('div');
        panel.id = 'itsecursas-chatbot-panel';
        panel.style.cssText = 'position:fixed;bottom:88px;right:20px;width:360px;max-height:70vh;background:#fff;border:1px solid #dee2e6;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,.15);display:none;z-index:9999;overflow:hidden;';
        panel.innerHTML = '' +
            '<div style="background:#212529;color:#fff;padding:10px 12px;font-weight:600;">Asistente técnico</div>' +
            '<div id="itsecursas-chatbot-messages" style="padding:12px;height:320px;overflow:auto;background:#f8f9fa;"></div>' +
            '<div style="padding:10px;border-top:1px solid #e9ecef;background:#fff;">' +
            '  <div id="itsecursas-chatbot-actions" style="display:none;gap:8px;margin-bottom:8px;">' +
            '    <button id="itsecursas-chatbot-confirm" style="flex:1;border:1px solid #212529;background:#212529;color:#fff;border-radius:4px;padding:8px 10px;">Crear orden</button>' +
            '    <button id="itsecursas-chatbot-keep" style="flex:1;border:1px solid #ced4da;background:#fff;color:#212529;border-radius:4px;padding:8px 10px;">Seguir viendo</button>' +
            '  </div>' +
            '  <div style="display:flex;gap:8px;">' +
            '    <input id="itsecursas-chatbot-input" type="text" placeholder="Escribe aquí..." style="flex:1;border:1px solid #ced4da;border-radius:4px;padding:8px;">' +
            '    <button id="itsecursas-chatbot-send" style="border:1px solid #266bf9;background:#266bf9;color:#fff;border-radius:4px;padding:8px 12px;">Enviar</button>' +
            '  </div>' +
            '</div>';

        document.body.appendChild(panel);
        document.getElementById('itsecursas-chatbot-keep').addEventListener('click', function () {
            appendMessage('bot', 'Perfecto, sigue explorando. Estaré atento por si necesitas ayuda.');
            const panel = document.getElementById('itsecursas-chatbot-panel');
            if (panel) panel.style.display = 'none';
        });
        document.getElementById('itsecursas-chatbot-send').addEventListener('click', sendMessage);
        document.getElementById('itsecursas-chatbot-confirm').addEventListener('click', confirmOrder);

        chatInitialized = true;
    }

    function appendMessage(role, message) {
        const container = document.getElementById('itsecursas-chatbot-messages');
        if (!container) return;
        const bubble = document.createElement('div');
        bubble.style.cssText = 'margin-bottom:8px;padding:8px 10px;border-radius:8px;max-width:85%;' +
            (role === 'bot' ? 'background:#e9ecef;color:#212529;' : 'background:#266bf9;color:#fff;margin-left:auto;');
        bubble.innerText = message;
        container.appendChild(bubble);
        container.scrollTop = container.scrollHeight;
    }

    function appendHtml(role, html) {
        const container = document.getElementById('itsecursas-chatbot-messages');
        if (!container) return;
        const bubble = document.createElement('div');
        bubble.style.cssText = 'margin-bottom:8px;padding:8px 10px;border-radius:8px;max-width:92%;' +
            (role === 'bot' ? 'background:#e9ecef;color:#212529;' : 'background:#266bf9;color:#fff;margin-left:auto;');
        bubble.innerHTML = html;
        container.appendChild(bubble);
        container.scrollTop = container.scrollHeight;
    }

    function renderWishlistCards(products) {
        if (!Array.isArray(products) || products.length === 0) return;
        const cards = products.map(function (p) {
            const title = (p.title || 'Producto').replace(/</g, '&lt;');
            const price = (p.price || '').replace(/</g, '&lt;');
            return '' +
                '<div style="border:1px solid #ced4da;background:#fff;border-radius:6px;padding:8px;margin-bottom:6px;">' +
                '  <div style="font-weight:600;font-size:13px;color:#212529;">' + title + '</div>' +
                '  <div style="font-size:12px;color:#6c757d;">' + price + '</div>' +
                '</div>';
        }).join('');
        appendHtml('bot', '<div style="font-size:12px;margin-bottom:6px;">Estos son tus productos en me gusta:</div>' + cards);
    }

    function setActionButtonsVisibility(hasWishlist) {
        const actions = document.getElementById('itsecursas-chatbot-actions');
        if (!actions) return;
        actions.style.display = hasWishlist ? 'flex' : 'none';
    }

    function startOrResumeSession() {
        const csrfToken = SiteConfig?.auth?.csrfToken || '';
        return fetch('/api/chat/session/start-or-resume', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        }).then(r => r.json());
    }

    function syncWishlistBeforeChat() {
        const csrfToken = SiteConfig?.auth?.csrfToken || '';
        let productIds = [];
        try {
            const raw = localStorage.getItem('itsecursas-wishlist');
            productIds = raw ? JSON.parse(raw) : [];
            if (!Array.isArray(productIds)) productIds = [];
        } catch (e) {
            productIds = [];
        }

        return fetch('/api/wishlist/sync', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_ids: productIds })
        }).then(function (r) {
            return r.ok ? r.json() : null;
        }).catch(function () { return null; });
    }

    function openProactiveChat() {
        ensureChatUI();
        syncWishlistBeforeChat().finally(function () {
            startOrResumeSession().then(function (data) {
            if (!data || !data.success) return;
            currentSessionId = data.session_id;
            document.getElementById('itsecursas-chatbot-panel').style.display = 'block';
            const hasWishlist = Array.isArray(data.wishlist) && data.wishlist.length > 0;
            setActionButtonsVisibility(hasWishlist);
            if (!hasWelcomedForCurrentPrompt) {
                appendMessage('bot', 'Hola, soy tu Asistente tecnico.');
                if (hasWishlist) {
                    appendMessage('bot', 'Detecte que tienes articulos en me gusta. Te puedo ayudar a crear tu orden cuando quieras.');
                    setTimeout(function () {
                        renderWishlistCards(data.wishlist);
                    }, 5000);
                } else {
                    // Si no hay me gusta, solo se envía el saludo inicial.
                }
                hasWelcomedForCurrentPrompt = true;
            }
            }).catch(function () {});
        });
    }

    function openChatFromExternalTrigger() {
        hasWelcomedForCurrentPrompt = false;
        openProactiveChat();
    }

    function sendMessage() {
        const input = document.getElementById('itsecursas-chatbot-input');
        const text = (input.value || '').trim();
        if (!text || !currentSessionId) return;
        input.value = '';
        appendMessage('user', text);

        if (awaitingEmailForOrder) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(text)) {
                appendMessage('bot', 'Ese email no parece válido. Por favor escríbelo nuevamente para crear tu orden.');
                return;
            }
            awaitingEmailForOrder = false;
            confirmOrderWithEmail(text);
            return;
        }

        const csrfToken = SiteConfig?.auth?.csrfToken || '';
        fetch('/api/chat/message', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ session_id: currentSessionId, message: text })
        }).then(r => r.json()).then(function (data) {
            if (data && data.reply) appendMessage('bot', data.reply);
        }).catch(function () {});
    }

    function confirmOrderWithEmail(email) {
        if (!currentSessionId) return;
        const csrfToken = SiteConfig?.auth?.csrfToken || '';
        fetch('/api/chat/confirm-order', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ session_id: currentSessionId, email: email })
        }).then(async function (r) {
            const data = await r.json();
            if (!r.ok || !data.success) {
                appendMessage('bot', data.message || 'No pude crear la orden todavía.');
                return;
            }
            appendMessage('bot', 'Tu orden #' + data.order_id + ' fue creada. Te enviamos correo para seguimiento.');
        }).catch(function () {
            appendMessage('bot', 'Ocurrió un error al crear la orden. Intenta de nuevo.');
        });
    }

    function confirmOrder() {
        if (!currentSessionId) return;
        const knownEmail = SiteConfig?.auth?.email || null;
        if (knownEmail) {
            confirmOrderWithEmail(knownEmail);
            return;
        }
        awaitingEmailForOrder = true;
        appendMessage('bot', 'Para crear tu orden, confirma tu email:');
    }

    document.addEventListener('wishlist-updated', function () {
        hasWelcomedForCurrentPrompt = false;
        if (pendingTimer) clearTimeout(pendingTimer);
        pendingTimer = setTimeout(openProactiveChat, randomDelay());
    });

    // Permite abrir el chat desde otros componentes (ej: botón WhatsApp)
    window.itsecursasOpenChatbot = openChatFromExternalTrigger;

    document.addEventListener('itsecursas-open-chatbot', function () {
        openChatFromExternalTrigger();
    });
})();
