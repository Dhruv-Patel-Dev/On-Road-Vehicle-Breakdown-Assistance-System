<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Session Debug</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .debug { background: #f0f0f0; padding: 15px; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <h2>🔍 Session Debug Information</h2>
    
    <div class="debug">
        <h3>Session Data:</h3>
        <pre><?php print_r($_SESSION); ?></pre>
    </div>
    
    <?php if (isset($_SESSION['user_id']) && isset($_SESSION['role'])): ?>
        <div class="success">
            ✅ Session Active<br>
            User ID: <?php echo $_SESSION['user_id']; ?><br>
            Role: <?php echo $_SESSION['role']; ?><br>
            <?php if (isset($_SESSION['user_name'])): ?>
            Name: <?php echo $_SESSION['user_name']; ?><br>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="error">
            ❌ No active session found<br>
            Please <a href="../login.php">login again</a>
        </div>
    <?php endif; ?>
    
    <h3>🧪 Test API Endpoints:</h3>
    <p><a href="get_user_info.php" target="_blank">Test get_user_info.php</a></p>
    <p><a href="get_user_vehicles.php" target="_blank">Test get_user_vehicles.php</a></p>
    <p><a href="get_job_tracking.php" target="_blank">Test get_job_tracking.php</a></p>
    
    <script>
        // Test fetch calls
        fetch('get_user_info.php')
        .then(response => response.json())
        .then(data => {
            console.log('get_user_info.php result:', data);
            if (data.success) {
                document.getElementById('api-status').innerHTML = '<span class="success">✅ API Working - User: ' + data.user.name + '</span>';
            } else {
                document.getElementById('api-status').innerHTML = '<span class="error">❌ API Error: ' + data.error + '</span>';
            }
        })
        .catch(error => {
            console.error('API Error:', error);
            document.getElementById('api-status').innerHTML = '<span class="error">❌ API Connection Failed</span>';
        });
    </script>
    
    <h3>📡 API Status:</h3>
    <div id="api-status">Testing...</div>
</body>
</html>
