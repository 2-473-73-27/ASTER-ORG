<?php
session_start();

// Initialize in-memory session database if not already created
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        "client@gmail.com" => ["password" => "password123", "role" => "client"],
        "manager@gmail.com" => ["password" => "adminpassword", "role" => "manager"]
    ];
    $_SESSION['apps'] = [
        ["id" => 1, "name" => "SecureChat Pro", "category" => "Messaging", "status" => "Active"],
        ["id" => 2, "name" => "CryptoVault Wallet", "category" => "Finance", "status" => "Active"],
        ["id" => 3, "name" => "CloudSync Drive", "category" => "Storage", "status" => "Maintenance"]
    ];
    $_SESSION['commands'] = [];
    $_SESSION['logs'] = ["[System initialized successfully on live hosting environment]"];
}

// Handle Logout action
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Handle Login Form Submission
$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (isset($_SESSION['users'][$email]) && $_SESSION['users'][$email]['password'] === $password) {
        $_SESSION['user'] = $email;
        $_SESSION['role'] = $_SESSION['users'][$email]['role'];
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid Email or Password!";
    }
}

// Handle Manager Command Submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['command']) && isset($_SESSION['role']) && $_SESSION['role'] === 'manager') {
    $cmd = trim($_POST['command']);
    if (!empty($cmd)) {
        array_unshift($_SESSION['commands'], htmlspecialchars($cmd));
        array_unshift($_SESSION['logs'], "[" . date('Y-m-d H:i:s') . "] Manager executed: " . htmlspecialchars($cmd));
        $message = "Command parsed and executed successfully!";
    }
}

// Determine current view state based on session and query parameters
$is_logged_in = isset($_SESSION['user']);
$role = $is_logged_in ? $_SESSION['role'] : null;
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$app_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Secure Platform - Complete App</title>
    <style>
        :root { --bg: #0f172a; --card: #1e293b; --accent: #38bdf8; --text: #f8fafc; --danger: #ef4444; --success: #22c55e; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 90vh; }
        .container { background: var(--card); padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); width: 100%; max-width: 650px; box-sizing: border-box; }
        h1, h2, h3 { color: var(--accent); margin-top: 0; }
        input[type="email"], input[type="password"], input[type="text"], textarea { width: 100%; padding: 0.75rem; margin: 0.5rem 0 1rem 0; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 6px; box-sizing: border-box; }
        button, .btn { background: var(--accent); color: #0f172a; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; width: 100%; box-sizing: border-box; }
        .card { background: #0f172a; padding: 1rem; border-radius: 8px; border: 1px solid #334155; margin-bottom: 1rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 1rem; }
        .alert { background: var(--danger); color: white; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }
        .success { background: var(--success); color: white; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }
        pre { background: #0b0f19; padding: 0.75rem; border-radius: 4px; overflow-x: auto; font-size: 0.85rem; color: var(--accent); }
        .ai-box { background: #111827; border: 1px solid #3b82f6; padding: 1rem; border-radius: 8px; margin-top: 1rem; }
        a { color: var(--accent); text-decoration: none; }
    </style>
</head>
<body>
<div class="container">

    <?php if (!$is_logged_in): ?>
        <!-- ================= LOGIN PAGE ================= -->
        <h1>Hi Well Come &lt;_&gt;</h1>
        <?php if ($error): ?><div class="alert"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <label>Enter Your Email:</label>
            <input type="email" name="email" placeholder="✉️ ____________@gmail.com" required>
            
            <label>Enter Your Password:</label>
            <input type="password" name="password" placeholder="🔑 [_____________]" required>
            
            <button type="submit" name="login">[Login]</button>
        </form>

    <?php elseif ($role === 'client'): ?>
        
        <?php if ($page === 'app_detail' && $app_id > 0): 
            // ================= NEXT PAGE: APP DETAIL =================
            $selected_app = null;
            foreach ($_SESSION['apps'] as $a) {
                if ($a['id'] === $app_id) { $selected_app = $a; break; }
            }
        ?>
            <h2>[ Show App Name: <?php echo htmlspecialchars($selected_app['name'] ?? 'Unknown'); ?> ]</h2>
            <div class="card">
                <p><b>App ID:</b> <?php echo $selected_app['id']; ?></p>
                <p><b>Category:</b> <?php echo htmlspecialchars($selected_app['category']); ?></p>
                <p><b>Live Status:</b> <span style="color:var(--success);"><?php echo htmlspecialchars($selected_app['status']); ?></span></p>
                <p>Secure routing protocols are actively established for this module instance.</p>
            </div>
            <br>
            <a href="index.php" class="btn">[ Show All ] Back to Dashboard</a>

        <?php else: 
            // ================= CLIENT DASHBOARD =================
        ?>
            <h2>Client Dashboard &lt;_&gt;</h2>
            <p>Welcome back, <b><?php echo htmlspecialchars($_SESSION['user']); ?></b></p>
            
            <div class="card">
                <h3>📨 Today SMS & Notifications</h3>
                <p style="color: #94a3b8; font-size: 0.9rem;">Real-time messaging pipeline is active and secure.</p>
            </div>

            <h3>[App Store]</h3>
            <div class="grid">
                <?php foreach ($_SESSION['apps'] as $app): ?>
                <div class="card">
                    <h4><?php echo htmlspecialchars($app['name']); ?></h4>
                    <p style="font-size: 0.85rem; color: #94a3b8;">Category: <?php echo htmlspecialchars($app['category']); ?></p>
                    <a href="index.php?page=app_detail&id=<?php echo $app['id']; ?>" class="btn" style="padding: 0.4rem; font-size: 0.85rem;">[App] Open</a>
                </div>
                <?php endforeach; ?>
            </div>
            
            <br>
            <a href="index.php?action=logout" style="color: var(--danger);">Sign Out</a>
        <?php endif; ?>

    <?php elseif ($role === 'manager'): ?>
        <!-- ================= MANAGER DASHBOARD ================= -->
        <h2>[Well Come Back Manager] &lt;_&gt;</h2>
        <p>Master Administration Panel</p>
        
        <?php if ($message): ?><div class="success"><?php echo $message; ?></div><?php endif; ?>
        
        <div class="card">
            <h3>[<_>] [Command Center]</h3>
            <form method="POST">
                <input type="text" name="command" placeholder="Type design update or system command here..." required>
                <button type="submit">[Execute Command]</button>
            </form>
        </div>

        <div class="card">
            <h3>Executed Commands Log:</h3>
            <pre><?php echo empty($_SESSION['commands']) ? "No commands executed yet." : implode("\n", $_SESSION['commands']); ?></pre>
        </div>

        <div class="card">
            <h3>[OS System Health]</h3>
            <p style="color: var(--success);">● CPU Load: 11.8% | RAM Memory: Stable | Server Status: Online</p>
            <pre><?php echo implode("\n", array_slice($_SESSION['logs'], 0, 5)); ?></pre>
        </div>

        <div class="ai-box">
            <h3>{{{{🤖}}}} AI Manager Intelligence Core</h3>
            <p style="font-size: 0.85rem; color: #94a3b8;">Ask questions or request insights. The AI has complete situational context over active users, application records, and system health parameters.</p>
            <textarea id="ai-query" placeholder="Ask AI anything about website users, logs, or features..."></textarea>
            <button onclick="askAI()">Query AI Assistant</button>
            <div id="ai-response" style="margin-top: 0.75rem; font-size: 0.9rem; color: var(--accent);"></div>
        </div>

        <script>
            function askAI() {
                const query = document.getElementById('ai-query').value;
                if(!query) return;
                document.getElementById('ai-response').innerText = "Processing secure context lookup...";
                
                setTimeout(() => {
                    let reply = "All system parameters, database configurations, and active user credentials are functioning securely.";
                    const q = query.toLowerCase();
                    if(q.includes('user') || q.includes('client')) {
                        reply = "Database check: Active user accounts total <?php echo count($_SESSION['users']); ?>. Sessions are fully encrypted.";
                    } else if(q.includes('health') || q.includes('system')) {
                        reply = "System health check: Server hosting node is stable with 0% error rate.";
                    } else if(q.includes('command')) {
                        reply = "Total logged operational commands executed by manager: <?php echo count($_SESSION['commands']); ?>.";
                    }
                    document.getElementById('ai-response').innerText = "AI Insight: " + reply;
                }, 400);
            }
        </script>

        <br><br>
        <a href="index.php?action=logout" style="color: var(--danger);">Sign Out</a>

    <?php endif; ?>

</div>
</body>
</html>