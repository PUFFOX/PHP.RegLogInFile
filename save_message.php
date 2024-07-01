<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'send') 
{
    if (!isset($_SESSION['username'])) {
        echo "Ви повинні увійти для відправки повідомлення.";
        exit();
    }

    $message = htmlspecialchars($_POST['message']);
    $username = $_SESSION['username'];
    $dateNow = date('Y-m-d H:i:s');

    $xmlFile = 'Messages.xml';

    // Створення XML файлу, якщо він не існує
    if (!file_exists($xmlFile)) 
    {
        $xml = new SimpleXMLElement('<messages></messages>');
    } 
    else 
    {
        $xml = simplexml_load_file($xmlFile);
    }

    $msg = $xml->addChild('message');
    $msg->addAttribute('date', $dateNow);
    $msg->addChild('from', $username);
    $msg->addChild('content', $message);

    $xml->asXML($xmlFile);
    echo "Повідомлення відправлено.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Save Message</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="form-container">
        <a href="index.php">Назад до головної сторінки</a>
    </div>
</body>
</html>
