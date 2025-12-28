/**
 * Notification System
 * Shows toast notifications in the top-right corner
 */

function showNotification(
  message,
  type = "success",
  title = "",
  duration = 4000
) {
  // Create container if it doesn't exist
  let container = document.querySelector(".notification-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "notification-container";
    document.body.appendChild(container);
  }

  // Set default titles based on type
  if (!title) {
    switch (type) {
      case "success":
        title = "Success!";
        break;
      case "error":
        title = "Error!";
        break;
      case "warning":
        title = "Warning!";
        break;
      case "info":
        title = "Information";
        break;
      default:
        title = "Notification";
    }
  }

  // Get icon based on type
  const icons = {
    success: "fa-check-circle",
    error: "fa-times-circle",
    warning: "fa-exclamation-triangle",
    info: "fa-info-circle",
  };

  // Create notification element
  const notification = document.createElement("div");
  notification.className = `notification ${type}`;
  notification.innerHTML = `
        <i class="fas ${icons[type]} notification-icon"></i>
        <div class="notification-content">
            <div class="notification-title">${title}</div>
            <div class="notification-message">${message}</div>
        </div>
        <i class="fas fa-times notification-close"></i>
    `;

  // Add to container
  container.appendChild(notification);

  // Close button functionality
  const closeBtn = notification.querySelector(".notification-close");
  closeBtn.addEventListener("click", () => {
    removeNotification(notification);
  });

  // Auto remove after duration
  if (duration > 0) {
    setTimeout(() => {
      removeNotification(notification);
    }, duration);
  }
}

function removeNotification(notification) {
  notification.classList.add("slide-out");
  setTimeout(() => {
    notification.remove();

    // Remove container if empty
    const container = document.querySelector(".notification-container");
    if (container && container.children.length === 0) {
      container.remove();
    }
  }, 300);
}

// Convenience functions
function showSuccess(message, title = "") {
  showNotification(message, "success", title);
}

function showError(message, title = "") {
  showNotification(message, "error", title);
}

function showWarning(message, title = "") {
  showNotification(message, "warning", title);
}

function showInfo(message, title = "") {
  showNotification(message, "info", title);
}
