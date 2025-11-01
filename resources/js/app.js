import "./bootstrap";

import Alpine from "alpinejs";
import "../css/app.css";
import Swal from "sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
import "./confirm";
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

// Real-time notifications via Laravel Echo (Pusher or compatible)
if (typeof window !== "undefined") {
    window.Pusher = Pusher;
    const key = import.meta.env.VITE_PUSHER_APP_KEY;
    const cluster = import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1";
    const host = import.meta.env.VITE_PUSHER_HOST; // if set => self-hosted (Soketi/websockets)
    const port =
        import.meta.env.VITE_PUSHER_PORT ??
        (location.protocol === "https:" ? 443 : 80);
    const scheme =
        import.meta.env.VITE_PUSHER_SCHEME ??
        (location.protocol === "https:" ? "https" : "http");

    const metaUser = document.querySelector('meta[name="user-id"]');
    const userId =
        (window.App && window.App.userId) ||
        (metaUser ? metaUser.getAttribute("content") : null);

    if (key && userId) {
        const isSelfHosted = !!host;
        const echoOptions = isSelfHosted
            ? {
                  broadcaster: "pusher",
                  key,
                  cluster,
                  wsHost: host,
                  wsPort: port,
                  wssPort: port,
                  forceTLS: scheme === "https",
                  enabledTransports: ["ws", "wss"],
              }
            : {
                  broadcaster: "pusher",
                  key,
                  cluster,
                  forceTLS: true,
              };

        // Inicialización de Echo
        window.Echo = new Echo(echoOptions);

        // Prueba simple de depuración de la conexión
        try {
            const pusher = window.Echo.connector.pusher;
            pusher.connection.bind("connected", () =>
                console.debug("Echo connected")
            );
            pusher.connection.bind("error", (err) =>
                console.warn("Echo error", err)
            );
        } catch {}

        // Escucha de notificaciones transmitidas
        const channel = window.Echo.private(`App.Models.User.${userId}`);
        channel
            .subscribed(() => console.debug("Subscribed to user channel"))
            .error((err) => console.warn("Channel error", err))
            .notification((notification) => {
                // Normalización del payload: Laravel transmite las notificaciones con un sobre `data`
                const payload =
                    notification && notification.data
                        ? notification.data
                        : notification;
                try {
                    // Notificación tipo toast
                    if (window.Swal) {
                        window.Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon:
                                payload.status === "aceptada"
                                    ? "success"
                                    : payload.status === "rechazada"
                                    ? "error"
                                    : "info",
                            title: payload.title || "Notificación",
                            text: payload.body || "",
                            showConfirmButton: false,
                            timer: 4000,
                        });
                    }
                    // Incrementar el badge de notificaciones si existe
                    const badge = document.querySelector(
                        "[data-notifications-badge]"
                    );
                    if (badge) {
                        const val = parseInt(badge.textContent || "0", 10) || 0;
                        badge.textContent = String(val + 1);
                        badge.classList.remove("hidden");
                    }

                    // Añadir notificación al UI (desplegable y bandeja de entrada)
                    appendNotificationToUI(notification, payload);

                    // Actualizar también el estado de la justificación
                    if (payload && payload.justification_id && payload.status) {
                        updateJustificationStatusPill(
                            payload.justification_id,
                            payload.status,
                            payload.reason
                        );
                    }
                } catch (e) {
                    console.warn("Notification handling error:", e);
                }
            })
            .listen(".UserNotified", (e) => {
                try {
                    const data = e.payload || e;
                    if (window.Swal) {
                        window.Swal.fire({
                            toast: true,
                            position: "top-end",
                            icon:
                                data.status === "aceptada"
                                    ? "success"
                                    : data.status === "rechazada"
                                    ? "error"
                                    : "info",
                            title: data.title || "Notificación",
                            text: data.body || "",
                            showConfirmButton: false,
                            timer: 4000,
                        });
                    }
                    const badge = document.querySelector(
                        "[data-notifications-badge]"
                    );
                    if (badge) {
                        const val = parseInt(badge.textContent || "0", 10) || 0;
                        badge.textContent = String(val + 1);
                        badge.classList.remove("hidden");
                    }

                    // Actualizar también el estado de la justificación
                    if (data && data.justification_id && data.status) {
                        updateJustificationStatusPill(
                            data.justification_id,
                            data.status,
                            data.reason
                        );
                    }
                } catch (err) {
                    console.warn("UserNotified handling error:", err);
                }
            });
    }
}

// Actualiza el estado de la "status pill" de una justificación en "Mis Justificaciones"
function updateJustificationStatusPill(id, status, reason) {
    try {
        const card = document.querySelector(`[data-justificacion-id="${id}"]`);
        if (!card) return;
        const pill = card.querySelector("[data-justificacion-status]");
        if (!pill) return;

        // Eliminar clases de color previas
        pill.classList.remove(
            "bg-green-100",
            "text-green-800",
            "bg-red-100",
            "text-red-800",
            "bg-yellow-100",
            "text-yellow-800"
        );

        // Aplicar nuevas clases de color según el estado
        if (status === "aceptada") {
            pill.classList.add("bg-green-100", "text-green-800");
        } else if (status === "rechazada") {
            pill.classList.add("bg-red-100", "text-red-800");
        } else {
            pill.classList.add("bg-yellow-100", "text-yellow-800");
        }

        // Actualizar el texto de la pill
        const uc = status.charAt(0).toUpperCase() + status.slice(1);
        pill.textContent = uc;

        // Mostrar/ocultar bloque de detalles de rechazo dinámicamente
        const rej = card.querySelector("[data-justificacion-rechazo]");
        const rejText = card.querySelector("[data-justificacion-rechazo-text]");
        if (status === "rechazada") {
            if (rej) rej.classList.remove("hidden");
            if (
                rejText &&
                typeof reason === "string" &&
                reason.trim().length > 0
            ) {
                rejText.textContent = reason;
            }
        } else {
            if (rej) rej.classList.add("hidden");
        }
    } catch (err) {
        console.warn("Failed to update status pill:", err);
    }
}

// Si los contenedores de notificaciones están presentes, añade la nueva notificación al UI (desplegable y bandeja de entrada)
function appendNotificationToUI(notification, payload) {
    try {
        // Dropdown (top-right)
        const ddList = document.querySelector(
            "[data-notifications-dropdown-list]"
        );
        if (ddList) {
            const empty = ddList.querySelector(
                "[data-notifications-dropdown-empty]"
            );
            if (empty) empty.remove();
            const item = document.createElement("div");
            item.className =
                "px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700/50 flex gap-2";
            item.innerHTML = `
				<div class="mt-1">
					${
                        payload.status === "aceptada"
                            ? '<i class="fa-solid fa-circle-check text-green-600"></i>'
                            : payload.status === "rechazada"
                            ? '<i class="fa-solid fa-circle-xmark text-red-600"></i>'
                            : '<i class="fa-solid fa-bell text-indigo-600"></i>'
                    }
				</div>
				<div class="flex-1">
					<div class="text-sm font-medium">${escapeHtml(
                        payload.title || "Notificación"
                    )}</div>
					<div class="text-xs text-gray-600 dark:text-gray-300">${escapeHtml(
                        payload.body || ""
                    )}</div>
					<div class="text-[10px] text-gray-400">hace un momento</div>
				</div>`;
            ddList.prepend(item);
        }

        // Inbox page (/notifications)
        const inbox = document.querySelector("[data-notifications-list]");
        if (inbox) {
            const emptyMsg = document.querySelector(
                "[data-notifications-empty]"
            );
            if (emptyMsg) emptyMsg.remove();
            const row = document.createElement("div");
            row.className =
                "py-3 flex items-start gap-3 bg-indigo-50 dark:bg-indigo-900/20 rounded";
            const url = payload.url
                ? `<a href="${payload.url}" class="text-sm text-indigo-600 hover:underline">Abrir</a>`
                : "";
            let markReadForm = "";
            const id = notification && notification.id ? notification.id : null;
            if (id) {
                const csrf =
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "";
                markReadForm = `
					<form method="POST" action="/notifications/${id}/read">
						<input type="hidden" name="_token" value="${csrf}">
						<button class="text-sm text-gray-600 hover:text-gray-800 js-confirm" data-confirm="¿Marcar como leída?">Marcar como leída</button>
					</form>`;
            }
            row.innerHTML = `
				<div class="mt-1">
					${
                        payload.status === "aceptada"
                            ? '<i class="fa-solid fa-circle-check text-green-600"></i>'
                            : payload.status === "rechazada"
                            ? '<i class="fa-solid fa-circle-xmark text-red-600"></i>'
                            : '<i class="fa-solid fa-bell text-indigo-600"></i>'
                    }
				</div>
				<div class="flex-1">
					<div class="font-medium">${escapeHtml(payload.title || "Notificación")}</div>
					<div class="text-sm text-gray-600 dark:text-gray-300">${escapeHtml(
                        payload.body || ""
                    )}</div>
					<div class="text-xs text-gray-500 mt-1">hace un momento</div>
				</div>
				<div class="flex items-center gap-2">${url}${
                markReadForm ||
                '<span class="text-xs text-gray-400">Leer</span>'
            }</div>
			`;
            inbox.prepend(row);
        }
    } catch (err) {
        console.warn("Failed to append notification into UI:", err);
    }
}

// Helper: naive HTML escape
function escapeHtml(str) {
    return String(str)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
