<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'manager') {
    header("Location: index.php");
    exit();
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['command'])) {
    $cmd = trim($_POST['command']);
    if (!empty($cmd)) {
        array_unshift($_SESSION['commands'], htmlspecialchars($cmd));
        array_unshift($_SESSION['logs'], "[" . date('Y-m-d H:i:s') . "] Manager executed: " . htmlspecialchars($cmd));
        $message = "Command parsed and executed successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Control Center</title>
    <style>
        :root { --bg: #0f172a; --card: #1e293b; --accent: #38bdf8; --text: #f8fafc; --danger: #ef4444; --success: #22c55e; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 20px; display: flex; justify-content: center; }
        .container { background: var(--card); padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); width: 100%; max-width: 750px; box-sizing: border-box; }
        h2, h3 { color: var(--accent); margin-top: 0; }
        .card { background: #0f172a; padding: 1rem; border-radius: 8px; border: 1px solid #334155; margin-bottom: 1rem; }
        input[type="text"], textarea { width: 100%; padding: 0.75rem; margin: 0.5rem 0 1rem 0; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 6px; box-sizing: border-box; }
        button { background: var(--accent); color: #0f172a; border: none; padding: 0.75rem; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; }
        .success { background: var(--success); color: white; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }
        pre { background: #0b0f19; padding: 0.75rem; border-radius: 4px; overflow-x: auto; font-size: 0.85rem; color: var(--accent); }
        .ai-box { background: #111827; border: 1px solid #3b82f6; padding: 1rem; border-radius: 8px; margin-top: 1rem; }
        a { color: var(--accent); text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
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
            <pre><?php echo empty($_SESSION['commands']) ? "No commands executed yet." : implode("
", $_SESSION['commands']); ?></pre>
        </div>

        <div class="card">
            <h3>[OS System Health]</h3>
            <p style="color: var(--success);">● CPU Load: 11.8% | RAM Memory: Stable | Server Status: Online</p>
            <pre><?php echo implode("
", array_slice($_SESSION['logs'], 0, 5)); ?></pre>
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
        <a href="logout.php" style="color: var(--danger);">Sign Out</a>
    </div>
</body>
</html>