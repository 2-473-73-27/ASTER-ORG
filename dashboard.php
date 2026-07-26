<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'client') {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard</title>
    <style>
        :root { --bg: #0f172a; --card: #1e293b; --accent: #38bdf8; --text: #f8fafc; --danger: #ef4444; --success: #22c55e; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 20px; display: flex; justify-content: center; }
        .container { background: var(--card); padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); width: 100%; max-width: 700px; box-sizing: border-box; }
        h2, h3 { color: var(--accent); margin-top: 0; }
        .card { background: #0f172a; padding: 1rem; border-radius: 8px; border: 1px solid #334155; margin-bottom: 1rem; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-top: 1rem; }
        .btn { background: var(--accent); color: #0f172a; border: none; padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; }
        a { color: var(--accent); text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
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
                <a href="app_detail.php?id=<?php echo $app['id']; ?>" class="btn">[App] Open</a>
            </div>
            <?php endforeach; ?>
        </div>
        
        <br>
        <a href="logout.php" style="color: var(--danger);">Sign Out</a>
    </div>
</body>
</html>