<?php
// Veritabanı bağlantısı için ayarlar
$db = new SQLite3('Login.sqlite');

// Tabloyu oluşturmak için SQL sorgusu
$query = "CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    password TEXT NOT NULL
)";

$db->exec($query);
// Admin kullanıcısının kullanıcı adı ve şifresi
$adminUsername = "admin"; // Kullanıcı adını buraya yazın
$adminPassword = password_hash("admin123", PASSWORD_DEFAULT); // Şifreyi buraya yazın

// Admin kullanıcısını tabloya ekle
$query = "INSERT INTO users (username, password) VALUES (:username, :password)";
$statement = $db->prepare($query);
$statement->bindValue(':username', $adminUsername, SQLITE3_TEXT);
$statement->bindValue(':password', $adminPassword, SQLITE3_TEXT);
$statement->execute();

// Kullanıcı girişi yapıldığında bu kod çalışacak
session_start();

// Kullanıcı daha önceden giriş yapmışsa ana sayfaya yönlendir
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: admin.php");
    exit;
}

// Form gönderildiğinde kullanıcı adı ve şifreyi doğrula
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Kullanıcı adı ve şifre doğrulaması
    $query = "SELECT * FROM users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $statement->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row && password_verify($password, $row["password"])) {
        // Kullanıcı girişi başarılı, oturumu başlat
        $_SESSION["loggedin"] = true;
        $_SESSION["username"] = $username;
        header("location: admin.php");
        exit;
    } else {
        // Kullanıcı adı veya şifre hatalı, hata mesajı göster
        $error_message = "Kullanıcı adı veya şifre hatalı.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kullanıcı Girişi</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
    }

    h1 {
        text-align: center;
        margin-top: 50px;
        color: #333;
    }

    form {
        max-width: 400px;
        margin: 30px auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    label {
        display: block;
        font-size: 16px;
        margin-bottom: 10px;
        color: #333;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="submit"] {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    p {
        text-align: center;
        color: red;
        margin-top: 10px;
    }
</style>

</head>
<body>
    <h1>Kullanıcı Girişi</h1>
    <?php if (isset($error_message)) : ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Kullanıcı Adı:</label>
        <input type="text" name="username" required>
        <br>
        <label for="password">Şifre:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Giriş Yap">
    </form>
</body>
</html>

