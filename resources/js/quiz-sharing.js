// resources/js/quiz-sharing.js
// Copy PIN to clipboard
window.copyPIN = function (pin) {
    navigator.clipboard
        .writeText(pin)
        .then(() => {
            showNotification("تم نسخ رمز الدخول!", "success");
        })
        .catch(() => {
            // Fallback for older browsers
            const input = document.createElement("input");
            input.value = pin;
            document.body.appendChild(input);
            input.select();
            document.execCommand("copy");
            document.body.removeChild(input);
            showNotification("تم نسخ رمز الدخول!", "success");
        });
};

// Share quiz
window.shareQuiz = function (pin, title) {
    const url = `${window.location.origin}/?pin=${pin}`;
    const text = `انضم لاختبار: ${title}\nرمز الدخول: ${pin}\n`;

    if (navigator.share) {
        navigator
            .share({
                title: title,
                text: text,
                url: url,
            })
            .catch(() => {
                // User cancelled sharing
            });
    } else {
        // Fallback - copy to clipboard
        const fullText = text + url;
        navigator.clipboard.writeText(fullText).then(() => {
            showNotification("تم نسخ رابط المشاركة!", "success");
        });
    }
};

// Notification helper
window.showNotification = function (message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `fixed top-4 right-4 px-6 py-4 rounded-lg shadow-lg z-50 animate-fade-in ${
        type === "success" ? "bg-green-500" : "bg-blue-500"
    } text-white`;
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${
                type === "success" ? "fa-check-circle" : "fa-info-circle"
            }"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.add(
            "opacity-0",
            "transition-opacity",
            "duration-300"
        );
        setTimeout(() => notification.remove(), 300);
    }, 3000);
};
