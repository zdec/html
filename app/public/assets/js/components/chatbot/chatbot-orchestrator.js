/**
 * Chatbot Orchestrator
 * Detecta likes, espera 20-30s y acompaña al cliente.
 */
(function () {
    const CHAT_DELAY_MS = 20000;
    let pendingTimer = null;
    let currentSessionId = null;
    let chatInitialized = false;
    let hasWelcomedForCurrentPrompt = false;
    let awaitingEmailForOrder = false;
    let lastWishlistSignature = '';

    function clearChatMessages() {
        const container = document.getElementById('itsecursas-chatbot-messages');
        if (container) container.innerHTML = '';
    }

    function ensureChatUI() {
        if (chatInitialized) return;
        const panel = document.createElement('div');
        panel.id = 'itsecursas-chatbot-panel';
        panel.style.cssText = 'position:fixed;bottom:88px;right:20px;width:360px;max-height:70vh;background:#fff;border:1px solid #dee2e6;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,.15);display:none;z-index:9999;overflow:hidden;';
        panel.innerHTML = '' +
            '<div id="itsecursas-chatbot-header" style="position:relative;background:#212529;color:#fff;padding:10px 12px;font-weight:600;display:flex;align-items:center;justify-content:space-between;cursor:pointer;">' +
            '  <span>Asistente técnico</span>' +
            '  <div style="display:flex;gap:10px;align-items:center;">' +
            '    <button id="itsecursas-chatbot-minimize" aria-label="Minimizar" title="Minimizar" style="position:relative;z-index:2;border:none;background:transparent;color:#fff;font-size:18px;line-height:1;cursor:pointer;padding:0;">&minus;</button>' +
            '    <button id="itsecursas-chatbot-close" aria-label="Cerrar" title="Cerrar" style="position:relative;z-index:2;border:none;background:transparent;color:#fff;font-size:18px;line-height:1;cursor:pointer;padding:0;">&times;</button>' +
            '  </div>' +
            '</div>' +
            '<div id="itsecursas-chatbot-body">' +
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
            '</div>' +
            '</div>';

        document.body.appendChild(panel);
        document.getElementById('itsecursas-chatbot-keep').addEventListener('click', function () {
            appendMessage('bot', 'Perfecto, sigue explorando. Estaré atento por si necesitas ayuda.');
            const panel = document.getElementById('itsecursas-chatbot-panel');
            if (panel) panel.style.display = 'none';
        });
        document.getElementById('itsecursas-chatbot-send').addEventListener('click', sendMessage);
        document.getElementById('itsecursas-chatbot-input').addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                sendMessage();
            }
        });
        document.getElementById('itsecursas-chatbot-minimize').addEventListener('click', function () {
            const body = document.getElementById('itsecursas-chatbot-body');
            if (!body) return;
            const isHidden = body.style.display === 'none';
            body.style.display = isHidden ? 'block' : 'none';
        });
        document.getElementById('itsecursas-chatbot-header').addEventListener('click', function (event) {
            const target = event.target;
            if (target && (target.id === 'itsecursas-chatbot-close' || target.id === 'itsecursas-chatbot-minimize')) {
                return;
            }
            const body = document.getElementById('itsecursas-chatbot-body');
            if (!body) return;
            if (body.style.display === 'none') {
                body.style.display = 'block';
            }
        });
        document.getElementById('itsecursas-chatbot-close').addEventListener('click', function () {
            const panel = document.getElementById('itsecursas-chatbot-panel');
            const body = document.getElementById('itsecursas-chatbot-body');
            if (body) body.style.display = 'block';
            if (panel) panel.style.display = 'none';
        });
        document.getElementById('itsecursas-chatbot-confirm').addEventListener('click', confirmOrder);

        chatInitialized = true;
    }

    function escapeHtml(text) {
        return String(text || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function linkifyProductPaths(text) {
        const escaped = escapeHtml(text);
        return escaped
            .replace(/\n/g, '<br>')
            .replace(/(\/producto\/[a-z0-9\-]+)/gi, function (path) {
                return '<a href="' + path + '" style="color:#0d6efd;text-decoration:underline;">' + path + '</a>';
            });
    }

    function appendMessage(role, message) {
        const container = document.getElementById('itsecursas-chatbot-messages');
        if (!container) return;
        const bubble = document.createElement('div');
        bubble.style.cssText = 'margin-bottom:8px;padding:8px 10px;border-radius:8px;max-width:85%;' +
            (role === 'bot' ? 'background:#e9ecef;color:#212529;' : 'background:#266bf9;color:#fff;margin-left:auto;');
        if (role === 'bot') {
            bubble.innerHTML = linkifyProductPaths(message);
        } else {
            bubble.innerText = message;
        }
        container.appendChild(bubble);
        container.scrollTop = container.scrollHeight;
    }

    function appendBotMessageProgressive(message) {
        const chunks = String(message || '')
            .split(/\n{2,}/)
            .map(function (part) { return part.trim(); })
            .filter(Boolean)
            .slice(0, 4);

        if (chunks.length <= 1) {
            appendMessage('bot', message);
            return;
        }

        let accumulatedDelay = 0;
        chunks.forEach(function (chunk, index) {
            const isLinksBlock = /^puedes verlos aqu[ií]/i.test(chunk);
            const delayBeforeThisChunk = index === 0 ? 0 : (isLinksBlock ? 5000 : 550);
            accumulatedDelay += delayBeforeThisChunk;
            setTimeout(function () {
                appendMessage('bot', chunk);
            }, accumulatedDelay);
        });
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
            const image = (p.image || '').replace(/"/g, '&quot;');
            const imageHtml = image
                ? '<img src="' + image + '" alt="' + title + '" style="width:48px;height:48px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;flex:0 0 48px;">'
                : '<div style="width:48px;height:48px;border-radius:6px;border:1px solid #dee2e6;background:#f1f3f5;flex:0 0 48px;"></div>';
            return '' +
                '<div style="border:1px solid #ced4da;background:#fff;border-radius:6px;padding:8px;margin-bottom:6px;">' +
                '  <div style="display:flex;gap:8px;align-items:center;">' +
                '    ' + imageHtml +
                '    <div>' +
                '      <div style="font-weight:600;font-size:13px;color:#212529;line-height:1.2;">' + title + '</div>' +
                '      <div style="font-size:12px;color:#6c757d;margin-top:2px;">' + price + '</div>' +
                '    </div>' +
                '  </div>' +
                '</div>';
        }).join('');
        appendHtml('bot', '<div style="font-size:12px;margin-bottom:6px;">Estos son tus productos en me gusta:</div>' + cards);
    }

    function setActionButtonsVisibility(hasWishlist) {
        const actions = document.getElementById('itsecursas-chatbot-actions');
        if (!actions) return;
        actions.style.display = hasWishlist ? 'flex' : 'none';
    }

    function wishlistSignature(products) {
        if (!Array.isArray(products) || products.length === 0) return '';
        return products
            .map(function (p) { return String(p.id || ''); })
            .filter(Boolean)
            .sort()
            .join(',');
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
            const parsed = raw ? JSON.parse(raw) : [];
            const toId = function (item) {
                if (typeof item === 'number') return item;
                if (typeof item === 'string' && /^\d+$/.test(item)) return parseInt(item, 10);
                if (item && typeof item === 'object') {
                    const candidate = item.id ?? item.product_id ?? item.productId;
                    if (typeof candidate === 'number') return candidate;
                    if (typeof candidate === 'string' && /^\d+$/.test(candidate)) return parseInt(candidate, 10);
                }
                return null;
            };
            productIds = Array.isArray(parsed)
                ? parsed.map(toId).filter(function (id) { return Number.isInteger(id) && id > 0; })
                : [];
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

    function openProactiveChat(options) {
        const opts = options || {};
        const forceWishlistPrompt = Boolean(opts.forceWishlistPrompt);
        ensureChatUI();
        syncWishlistBeforeChat().finally(function () {
            startOrResumeSession().then(function (data) {
            if (!data || !data.success) return;
            currentSessionId = data.session_id;
            document.getElementById('itsecursas-chatbot-panel').style.display = 'block';
            const hasWishlist = Array.isArray(data.wishlist) && data.wishlist.length > 0;
            setActionButtonsVisibility(hasWishlist);

            const history = Array.isArray(data.history) ? data.history : [];
            if (history.length > 0) {
                clearChatMessages();
                history.forEach(function (entry) {
                    if (!entry || !entry.role || !entry.content) return;
                    appendMessage(entry.role === 'user' ? 'user' : 'bot', entry.content);
                });
                hasWelcomedForCurrentPrompt = true;
            } else if (!hasWelcomedForCurrentPrompt) {
                appendMessage('bot', 'Hola, soy tu Asistente tecnico.');
                if (hasWishlist) {
                    appendMessage('bot', 'Detecte que tienes articulos en me gusta. Te puedo ayudar a crear tu orden cuando quieras.');
                    setTimeout(function () {
                        renderWishlistCards(data.wishlist);
                    }, 5000);
                }
                hasWelcomedForCurrentPrompt = true;
            }

            if (forceWishlistPrompt && hasWishlist) {
                const currentSignature = wishlistSignature(data.wishlist);
                if (currentSignature !== '' && currentSignature !== lastWishlistSignature) {
                    appendMessage('bot', 'Vi tus productos en me gusta. Si quieres, te preparo la orden con estos artículos:');
                    setTimeout(function () {
                        renderWishlistCards(data.wishlist);
                    }, 900);
                    lastWishlistSignature = currentSignature;
                }
            }
            }).catch(function () {});
        });
    }

    function openChatFromExternalTrigger() {
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
            if (data && data.reply) appendBotMessageProgressive(data.reply);
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
            // La orden ya se creó: limpiamos wishlist local para evitar reproponer la misma orden.
            try {
                localStorage.setItem('itsecursas-wishlist', JSON.stringify([]));
            } catch (e) {}
            lastWishlistSignature = '';
            setActionButtonsVisibility(false);
            document.dispatchEvent(new CustomEvent('wishlist-updated'));
            appendMessage('bot', data.message || ('Tu orden #' + data.order_id + ' fue creada. Para ver su estado, entra por Mi cuenta: /login'));
        }).catch(function () {
            appendMessage('bot', 'Ocurrió un error al crear la orden. Intenta de nuevo.');
        });
    }

    function confirmOrder() {
        if (!currentSessionId) return;
        setActionButtonsVisibility(false);
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
        pendingTimer = setTimeout(function () {
            openProactiveChat({ forceWishlistPrompt: true });
        }, CHAT_DELAY_MS);
    });

    // Permite abrir el chat desde otros componentes (ej: botón WhatsApp)
    window.itsecursasOpenChatbot = openChatFromExternalTrigger;

    document.addEventListener('itsecursas-open-chatbot', function () {
        openChatFromExternalTrigger();
    });
})();
