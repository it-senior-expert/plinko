// game_logic.js
function initializeGame(settings) {
    console.log('Game initialized with settings:', settings);
    verifyUserBalance(settings.userId, settings.money, settings.homeUrl);
    // Set session or cookie for user recognition
    document.cookie = "session=" + settings.session + "; path=/";
}

function verifyUserBalance(userId, initialMoney, homeUrl) {
    fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({action: 'get_balance', userId: userId})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.balance >= initialMoney) {
                updateUIBalance(data.balance);  // Update balance in UI
            } else {
                displayNotification('Error', 'NOT LOGGED IN');  // Display error message
            }
        } else {
                window.location.href = homeUrl;  // Redirect to home URL
        }
    });
}

function postTransaction(transactionData, homeUrl) {
    fetch('api.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(Object.assign({action: 'update_transaction'}, transactionData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            updateUIBalance(data.balance);  // Update the new balance after transaction
            displayNotification('Success', 'Transaction successful');  // Success notification
        } else {
            window.location.href = homeUrl;  // Redirect to home URL
        }
    });
}

function updateUIBalance(balance) {
    // game.Pay(balance);
}

// Helper function to display notifications
function displayNotification(type, message) {
    game.Charge(message);
    const notificationElement = document.getElementById('notification');
    notificationElement.textContent = message;
    notificationElement.className = type.toLowerCase();  // Apply styling based on type of notification
}
