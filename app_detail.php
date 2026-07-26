<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'client') {
    header("Location: index.php");
    exit();
}

$app_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$selected_app = null;
foreach ($_SESSION['apps'] as $app) {
    if ($app['id'] === $app_id) {
        $selected_app = $app;
        break;
    }
}

if (!$selected_app) {
    echo "App not found! <a href='dashboard.php'>Back to Dashboard</a>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Details</title>
    <style>
        :root { --bg: #0f172a; --card: #1e293b; --accent: #38bdf8; --text: #f8fafc; --success: #22c55e; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 20px; display: flex; justify-content: center; }
        .container { background: var(--card); padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); width: 100%; max-width: 600px; box-sizing: border-box; }
        h2 { color: var(--accent); margin-top: 0; }
        .card { background: #0f172a; padding: 1.25rem; border-radius: 8px; border: 1px solid #334155; margin-top: 1rem; }
        .btn { background: var(--accent); color: #0f172a; border: none; padding: 0.75rem 1.5rem; border-radius: 6px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <h2>[ Show App Name: <?php echo htmlspecialchars($selected_app['name']); ?> ]</h2>
        <div class="card">
            <p><b>App ID:</b> <?php echo $selected_app['id']; ?></p>
            <p><b>Category:</b> <?php echo htmlspecialchars($selected_app['category']); ?></p>
            <p><b>Deployment Status:</b> <span style="color:var(--success);"><?php echo htmlspecialchars($selected_app['status']); ?></span></p>
            <p>Secure routing protocols are actively established for this module instance.</p>
        </div>
        <br>
        <a href="dashboard.php" class="btn">[ Show All ] Back to Dashboard</a>
    </div>
</body>
</html>