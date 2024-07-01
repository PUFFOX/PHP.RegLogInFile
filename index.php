<?php
session_start();

$xmlFile = 'User.xml';

// Створення XML файлу, якщо він не існує
if (!file_exists($xmlFile)) 
{
    $xml = new SimpleXMLElement('<users></users>');
    $xml->asXML($xmlFile);
}

// Функція для додавання користувача
function registerUser($name, $password, $xmlFile) 
{
    $xml = simplexml_load_file($xmlFile);

    foreach ($xml->user as $user) 
    {
        if ($user['name'] == $name) 
        {
            echo "<script>alert('Користувач з таким ім\'ям вже існує.');</script>";
            return false;
        }
    }

    $user = $xml->addChild('user');
    $user->addAttribute('name', $name);
    $user->addChild('password', $password);
    $xml->asXML($xmlFile);

    echo "<script>alert('Користувач зареєстрований.');</script>";
    return true;
}

// Функція для перевірки логіну
function loginUser($name, $password, $xmlFile) 
{
    $xml = simplexml_load_file($xmlFile);

    foreach ($xml->user as $user) 
    {
        if ($user['name'] == $name && (string)$user->password == $password) 
        {
            $_SESSION['username'] = (string)$user['name'];
            return true;
        }
    }

    echo "<script>alert('Невірне ім\'я користувача або пароль!');</script>";
    return false;
}

// Обробка реєстрації та логіну
if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $name = htmlspecialchars($_POST['name']);
    $password = htmlspecialchars($_POST['password']);

    if ($_POST['action'] === 'register') 
    {
        registerUser($name, $password, $xmlFile);
    } elseif ($_POST['action'] === 'login') 
    {
        if (loginUser($name, $password, $xmlFile)) 
        {
            echo "<script>document.getElementById('sendButton').disabled = false;</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function enableSendButton() 
        {
            document.getElementById('sendButton').disabled = false;
        }
    </script>
</head>
<body>
    <div class="form-container">
        <h2>Форма реєстрації і входу користувача</h2>
        <form action="index.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br><br>

            <button type="submit" name="action" value="register">Register</button>
            <button type="submit" name="action" value="login">Login</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Форма для повідомлення</h2>
        <form action="save_message.php" method="post">
            <label for="message">Message:</label>
            <input type="text" id="message" name="message" oninput="enableSendButton()" required>

            <div class="btn-container">
                <button type="submit" id="sendButton" name="action" value="send" <?php if (!isset($_SESSION['username'])) echo 'disabled'; ?>>Send</button>
                <button type="button" onclick="location.reload();">Refresh</button>
            </div>
        </form>
    </div>
</body>
</html>
