// game_logic.js
function initializeGame(token) {
    game.config.token = token;
}

function postTransaction(transactionData, homeUrl) {
    fetch('php/api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(Object.assign({action: 'update_transaction'}, transactionData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayNotification('Success', 'Transaction successful');  // Success notification
        } else {
            window.location.href = homeUrl;  // Redirect to home URL
        }
    });
}

// Helper function to display notifications
function displayNotification(type, message) {
    game.Charge(message);
    const notificationElement = document.getElementById('notification');
    notificationElement.textContent = message;
    notificationElement.className = type.toLowerCase();  // Apply styling based on type of notification
}
