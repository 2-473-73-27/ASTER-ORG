<?php
session_start();

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

if (isset($_SESSION['user'])) {
    if ($_SESSION['role'] === 'manager') {
        header("Location: manager.php");
    } else {
        header("Location: dashboard.php");
    }
    exit();
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (isset($_SESSION['users'][$email]) && $_SESSION['users'][$email]['password'] === $password) {
        $_SESSION['user'] = $email;
        $_SESSION['role'] = $_SESSION['users'][$email]['role'];
        
        if ($_SESSION['role'] === 'manager') {
            header("Location: manager.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid Email or Password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Secure Platform</title>
    <style>
        :root { --bg: #0f172a; --card: #1e293b; --accent: #38bdf8; --text: #f8fafc; --danger: #ef4444; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: var(--bg); color: var(--text); margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .container { background: var(--card); padding: 2rem; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); width: 100%; max-width: 400px; box-sizing: border-box; }
        h1 { color: var(--accent); margin-top: 0; }
        input[type="email"], input[type="password"] { width: 100%; padding: 0.75rem; margin: 0.5rem 0 1rem 0; background: #0f172a; border: 1px solid #334155; color: white; border-radius: 6px; box-sizing: border-box; }
        button { background: var(--accent); color: #0f172a; border: none; padding: 0.75rem; border-radius: 6px; font-weight: bold; cursor: pointer; width: 100%; }
        .alert { background: var(--danger); color: white; padding: 0.5rem; border-radius: 4px; margin-bottom: 1rem; font-size: 0.9rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hi Well Come &lt;_&gt;</h1>
        <?php if ($error): ?><div class="alert"><?php echo $error; ?></div><?php endif; ?>
        <form method="POST">
            <label>Enter Your Email:</label>
            <input type="email" name="email" placeholder="✉️ ____________@gmail.com" required>
            <label>Enter Your Password:</label>
            <input type="password" name="password" placeholder="🔑 [_____________]" required>
            <button type="submit">[Login]</button>
        </form>
    </div>
</body>
</html>