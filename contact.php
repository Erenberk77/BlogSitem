<?php
// Veritabanı bağlantısı için ayarlar
$db = new SQLite3('database.sqlite');

// Tabloyu oluşturmak için SQL sorgusu
$query = "CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            fullname TEXT NOT NULL,
            email TEXT NOT NULL,
            subject TEXT NOT NULL,
            message TEXT NOT NULL,
            date TEXT NOT NULL
        )";

// SQL sorgusunu çalıştırın
$db->exec($query);

// Formdan veri gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname-surname"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Verileri SQLite veritabanına ekleyen sorgu
    $insertQuery = "INSERT INTO messages (fullname, email, subject, message, date) VALUES (:fullname, :email, :subject, :message, :date)";
    $statement = $db->prepare($insertQuery);
    $statement->bindValue(':fullname', $fullname, SQLITE3_TEXT);
    $statement->bindValue(':email', $email, SQLITE3_TEXT);
    $statement->bindValue(':subject', $subject, SQLITE3_TEXT);
    $statement->bindValue(':message', $message, SQLITE3_TEXT);
    $statement->bindValue(':date', date('Y-m-d H:i:s'), SQLITE3_TEXT);
    
    if ($statement->execute()) {
        // Veri başarıyla eklendi, kullanıcıyı teşekkür sayfasına yönlendir
        header("location: thank_you.html");
        exit;
    } else {
        // Veri eklenirken hata oluştu
        echo "Hata! Veri eklenemedi.";
    }
}
?>


