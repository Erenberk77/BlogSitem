<?php
// Veritabanı bağlantısı için ayarlar
$db = new SQLite3('database.sqlite');

// Kullanıcı girişi yapıldığında bu kod çalışacak

session_start();

// Kullanıcı girişi yapmadan admin paneline erişimi engelleme
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php"); // Kullanıcı giriş sayfasına yönlendir
    exit;
}

// Admin paneli işlemlerini burada yapacağız
// Eğer formdan bir ileti silme talebi gelirse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_message"])) {
    $message_id = $_POST["message_id"];

    // İletiyi veritabanından sil
    $query = "DELETE FROM messages WHERE id = :message_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':message_id', $message_id, SQLITE3_INTEGER);

    if ($statement->execute()) {
        // İleti başarıyla silindi
        header("location: admin.php"); // Admin paneline yönlendir
        exit;
    } else {
        // İletiyi silerken bir hata oluştu
        echo "Hata! İleti silinemedi.";
    }
}

// Admin panelinde iletileri listeleme işlemi
$query = "SELECT * FROM messages ORDER BY date DESC";
$result = $db->query($query);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["new_photo"])) {
    $target_dir = "assets\img"; // Yeni fotoğrafın yükleneceği dizin
    $target_file = $target_dir . basename($_FILES["new_photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Yeni fotoğrafı doğrulama ve yükleme işlemi
    if (move_uploaded_file($_FILES["new_photo"]["tmp_name"], $target_file)) {
        // Dosya başarıyla yüklendi, index.html dosyasını güncelleme işlemi yapılabilir
        $index_file_path = "index.html"; // index.html dosyasının tam yolu
        $photo_tag = '<img src="' . $target_file . '" alt="" srcset="">';
    
        // index.html dosyasının içeriğini güncelleme
        $index_content = file_get_contents($index_file_path);
        $index_content = preg_replace('/<img\s+src=".+?"\s+alt=""\s+srcset="">/', $photo_tag, $index_content);
        file_put_contents($index_file_path, $index_content);
    
        echo "Fotoğraf başarıyla güncellendi.";
    } else {
        echo "Dosya yüklenirken bir hata oluştu.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>


    <style>
    /* Temel Stil */
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

h3 {
    color: #555;
}

ul {
    list-style: none;
    padding: 0;
}

li {
    margin-bottom: 20px;
}

/* İleti Sil Butonu Stili */
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

/* Çıkış Yap Linki Stili */
a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Blog Ekle Butonu Stili */
form[action="add_post.php"] input[type="submit"] {
    background-color: #28a745;
}

form[action="add_post.php"] input[type="submit"]:hover {
    background-color: #218838;
}

   </style>
    
</head>
<body>
    <h1>Admin Panel</h1>
    <h3>Hoş geldiniz, <?php echo $_SESSION["username"]; ?></h3>
    
    <!-- Mevcut iletilerin listesi -->
    <h2>Mevcut İletiler</h2>
    <ul>
        <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
            <li>
                <?php echo "Gönderen: " . $row["fullname"]; ?><br>
                <?php echo "E-posta: " . $row["email"]; ?><br>
                <?php echo "Konu: " . $row["subject"]; ?><br>
                <?php echo "Mesaj: " . $row["message"]; ?><br>
                <?php echo "Tarih: " . $row["date"]; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
                    <input type="hidden" name="message_id" value="<?php echo $row["id"]; ?>">
                    <input type="submit" name="delete_message" value="Sil">
                </form>
            </li>
            <br>
            <?php endwhile; ?>
    </ul>

    <!-- Yeni blog gönderisi eklemek için form -->
    <!-- Yeni blog gönderisi eklemek için form -->
<h2>Blog İçeriği Ekle</h2>
<form action="add_post.php" method="post">
    <input type="submit" value="Gönderi Ekle / Sil">
</form>



    <br>

    <h2>Ana sayfa profil fotoğrafı güncelleme</h2>
     
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="new_photo" accept="image/*">
        <input type="submit" name="submit" value="Fotoğrafı Yükle">
    </form>


    <a href="logout.php">Çıkış Yap</a> <!-- Kullanıcıyı oturumdan çıkış yaptırmak için ayrı bir "logout.php" dosyası oluşturmanız gerekecek -->

</body>
</html>