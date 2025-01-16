// game_logic.js
function initializeGame(settings) {
    console.log('Game initialized with settings:', settings);
    // Set session or cookie for user recognition
    if (!document.cookie.includes("session")) {
        document.cookie = "session=" + settings.session + "; path=/";
    }
    // Check for existing session and restore game state if available
    const sessionCookie = document.cookie.split('; ').find(row => row.startsWith('session='));
    if (sessionCookie) {
        const sessionId = sessionCookie.split('=')[1];
        // Fetch and restore game state using sessionId
        fetch('php/api.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'restore_session', sessionId: sessionId})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Restore game state
                game.player.balance = data.balance;
                updateUIBalance(data.balance);
            }
        });
    }
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
