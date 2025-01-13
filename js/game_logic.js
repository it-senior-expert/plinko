// game_logic.js
function initializeGame(settings) {
    console.log('Game initialized with settings:', settings);
    checkUserBalance(settings.userId);
}

function checkUserBalance(userId) {
    fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'get_balance', userId: userId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateUIBalance(data.balance);  // Update balance in UI
        } else {
            displayNotification('Error', data.message);  // Display error message
        }
    });
}

function postTransaction(transactionData) {
    fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(Object.assign({action: 'update_transaction'}, transactionData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateUIBalance(data.newBalance);  // Update the new balance after transaction
            displayNotification('Success', 'Transaction successful');  // Success notification
        } else {
            displayNotification('Error', data.message);  // Error notification
        }
    });
}

function updateUIBalance(balance) {
    game.Pay(balance);
}

// Helper function to display notifications
function displayNotification(type, message) {
    const notificationElement = document.getElementById('notification');
    notificationElement.textContent = message;
    notificationElement.className = type.toLowerCase();  // Apply styling based on type of notification
}