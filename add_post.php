<?php
session_start();

// Kullanıcı girişi yapıldığında bu kod çalışacak
// Kullanıcı girişi yapmadan add_post sayfasına erişimi engelleme
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php"); // Kullanıcı giriş sayfasına yönlendir
    exit;
}

// Bu kısım add_post.php dosyanızdaki diğer kodlarınızla devam ediyor
// ...


// Veritabanı bağlantısı için ayarlar
$db = new SQLite3('database.sqlite');

// Eğer formdan gönderi eklemesi talebi geldiyse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_post"])) {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $date = date("Y-m-d H:i:s");

    // Gönderiyi veritabanına ekle
    $query = "INSERT INTO blog_posts (title, content, date) VALUES (:title, :content, :date)";
    $statement = $db->prepare($query);
    $statement->bindValue(':title', $title, SQLITE3_TEXT);
    $statement->bindValue(':content', $content, SQLITE3_TEXT);
    $statement->bindValue(':date', $date, SQLITE3_TEXT);

    if ($statement->execute()) {
        // Gönderi başarıyla eklendi, admin.php sayfasına yönlendir
        header("location: admin.php");
        exit;
    } else {
        // Gönderiyi eklerken bir hata oluştu
        echo "Hata! Gönderi eklenemedi.";
    }
}


// Eğer gönderi silme talebi geldiyse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_post"])) {
    $post_id = $_POST["post_id"];

    // Gönderiyi veritabanından sil
    $query = "DELETE FROM blog_posts WHERE id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id, SQLITE3_INTEGER);

    if ($statement->execute()) {
        // Gönderi başarıyla silindi, admin.php sayfasına yönlendir
        header("location: admin.php");
        exit;
    } else {
        // Gönderiyi silerken bir hata oluştu
        echo "Hata! Gönderi silinemedi.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Gönderi Ekle</title>
    <style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 0;
    padding: 0;
    background-color: #f1f1f1;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

h1 {
    color: #007bff;
}

h2 {
    color: #333;
    margin-bottom: 10px;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    margin-bottom: 20px;
}

/* Gönderi Ekle Formu */
form {
    margin-bottom: 20px;
}

label {
    display: block;
    font-weight: bold;
}

input[type="text"],
textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
}

input[type="submit"] {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 8px 16px;
    border-radius: 3px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

/* Silme Butonu */
form[action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"] input[type="submit"] {
    background-color: #dc3545;
}

form[action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"] input[type="submit"]:hover {
    background-color: #c82333;
}

/* Geri Dön Linki */
a {
    display: inline-block;
    margin-top: 10px;
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}
    </style>
   
</head>
<body>
    <h1>Gönderi Ekle</h1>

    <form action="add_post.php" method="post">
        <label for="title">Başlık:</label>
        <input type="text" name="title" required>
        <br>
        <label for="content">İçerik:</label>
        <textarea name="content" required></textarea>
        <br>
        <input type="submit" name="add_post" value="Gönderi Ekle">
    </form>

    <br>
    <h2>Mevcut Blog Gönderileri</h2>
<ul>
    <?php
    // Blog gönderilerini veritabanından çek
    $query = "SELECT * FROM blog_posts ORDER BY date DESC";
    $result = $db->query($query);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)):
    ?>
    <li>
        <?php echo "Başlık: " . $row["title"]; ?><br>
        <?php echo "İçerik: " . $row["content"]; ?><br>
        <?php echo "Tarih: " . $row["date"]; ?>

        <!-- Silme Butonu -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
            <input type="hidden" name="post_id" value="<?php echo $row["id"]; ?>">
            <input type="submit" name="delete_post" value="Sil">
        </form>
    </li>
    <br>
    <?php endwhile; ?>
</ul>
    <a href="admin.php">Admin Paneline Geri Dön</a>

</body>
</html>
