<!-- Notification System -->
<div id="notification-container" class="notification-container">
    <!-- Notifications will be dynamically added here -->
</div>

<style>
.notification-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 320px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.notification {
    background: white;
    border-radius: 8px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-left: 3px solid #111;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    animation: slideIn 0.3s ease-out;
    transition: all 0.3s ease;
    max-height: 120px;
    overflow: hidden;
}

.notification.removing {
    animation: slideOut 0.3s ease-out forwards;
}

.notification.success {
    border-left-color: #111;
}

.notification.error {
    border-left-color: #888;
}

.notification.info {
    background: #f5f5f5;
    border-left-color: #ccc;
}

.notification-icon {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
    margin-top: 2px;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: bold;
    color: #111;
    font-size: 14px;
    margin-bottom: 4px;
    line-height: 1.3;
}

.notification-message {
    color: #444;
    font-size: 13px;
    line-height: 1.4;
    word-wrap: break-word;
}

.notification-close {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
    flex-shrink: 0;
}

.notification-close:hover {
    background: rgba(0,0,0,0.1);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
        max-height: 120px;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
        max-height: 0;
        padding: 0;
        margin: 0;
    }
}

/* Ensure max 3 notifications visible */
.notification:nth-child(n+4) {
    display: none;
}
</style>

<script>
class NotificationSystem {
    constructor() {
        this.container = document.getElementById('notification-container');
        this.maxNotifications = 3;
        this.defaultDuration = 4000; // 4 seconds
    }

    show(message, type = 'info', title = '', duration = null) {
        const notification = this.createNotification(message, type, title);
        this.addNotification(notification);
        
        // Auto-remove after duration
        const removeDuration = duration || this.defaultDuration;
        setTimeout(() => {
            this.removeNotification(notification);
        }, removeDuration);
    }

    createNotification(message, type, title) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const icon = this.getIcon(type);
        
        notification.innerHTML = `
            <div class="notification-icon">${icon}</div>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" onclick="notificationSystem.removeNotification(this.parentElement)">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                    <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                </svg>
            </button>
        `;
        
        return notification;
    }

    getIcon(type) {
        const icons = {
            success: `<svg width="20" height="20" viewBox="0 0 20 20" fill="#111">
                <path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm3.707-9.293a1 1 0 0 0-1.414-1.414L9 10.586 7.707 9.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4z"/>
            </svg>`,
            error: `<svg width="20" height="20" viewBox="0 0 20 20" fill="#888">
                <path d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM8.707 7.293a1 1 0 0 0-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 1 0 1.414 1.414L10 11.414l1.293 1.293a1 1 0 0 0 1.414-1.414L11.414 10l1.293-1.293a1 1 0 0 0-1.414-1.414L10 8.586 8.707 7.293z"/>
            </svg>`,
            info: `<svg width="20" height="20" viewBox="0 0 20 20" fill="#666">
                <path d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0zm-7-4a1 1 0 1 0 0-2 0v2a1 1 0 1 0 0 2 0V6zm0 4a1 1 0 1 0 0-2 0v4a1 1 0 1 0 0 2 0v-4z"/>
            </svg>`
        };
        return icons[type] || icons.info;
    }

    addNotification(notification) {
        // Remove oldest if we have max notifications
        const notifications = this.container.children;
        if (notifications.length >= this.maxNotifications) {
            this.removeNotification(notifications[0]);
        }
        
        this.container.appendChild(notification);
    }

    removeNotification(notification) {
        if (!notification || !notification.parentElement) return;
        
        notification.classList.add('removing');
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 300);
    }

    // Convenience methods
    success(message, title = '', duration = null) {
        this.show(message, 'success', title, duration);
    }

    error(message, title = '', duration = null) {
        this.show(message, 'error', title, duration);
    }

    info(message, title = '', duration = null) {
        this.show(message, 'info', title, duration);
    }
}

// Global instance
window.notificationSystem = new NotificationSystem();

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Create container if it doesn't exist
    if (!document.getElementById('notification-container')) {
        const container = document.createElement('div');
        container.id = 'notification-container';
        container.className = 'notification-container';
        document.body.appendChild(container);
        window.notificationSystem.container = container;
    }
});
</script>
