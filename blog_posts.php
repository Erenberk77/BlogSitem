<?php
// Veritabanı bağlantısı için ayarlar
$db = new SQLite3('database.sqlite');

// Blog gönderilerini veritabanından çekme işlemi
function getBlogPosts() {
    global $db;
    $query = "SELECT * FROM blog_posts ORDER BY date DESC";
    $result = $db->query($query);
    $posts = array();
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $posts[] = $row;
    }
    return $posts;
}
?>
