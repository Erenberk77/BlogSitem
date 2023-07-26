<?php
// Veritabanı bağlantısı için ayarlar
$db = new SQLite3('database.sqlite');

if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Gönderiyi veritabanından çek
    $query = "SELECT * FROM blog_posts WHERE id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $post_id, SQLITE3_INTEGER);
    $result = $statement->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);

    if ($row) {
        $title = $row["title"];
        $content = $row["content"];
        $date = $row["date"];
        // 'author' ve 'reading_time' bilgilerini kontrol edelim
        $author = isset($row["author"]) ? $row["author"] : " ";
        $reading_time = isset($row["reading_time"]) ? $row["reading_time"] : " ";
    } else {
        // Gönderi bulunamadıysa hata mesajı göster veya yönlendirme yapabilirsiniz
        echo "Gönderi bulunamadı.";
        exit;
    }
} else {
    // URL parametresi yoksa hata mesajı göster veya yönlendirme yapabilirsiniz
    echo "Gönderi kimliği belirtilmedi.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Text</title>

    <link rel="shortcut icon" href="assets/img/icon/favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/fonts/flaticon/flaticon.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/mobile.css">
</head>
</head>

<body>

    <header>
        <!-- Header kısmı -->
    </header>

    <div class="blog-wrapper">
        <div class="container mt-4">
            <div class="blog-container">
                <div class="blog-top-title">
                    Blog
                </div>
                <div class="blog-container-text">
                    <div class="blog-text-meta-info">
                        <ul>
                            <li>
                                <div class="blog-text-date">
                                    <?php echo $date; ?>
                                </div>
                                <div class="blog-text-meta-dot">
                                    ·
                                </div>
                                <div class="blog-text-reading-time">
                                    <?php echo $reading_time; ?> 
                                </div>
                                <div class="blog-text-meta-dot">
                                    ·
                                </div>
                                <div class="blog-text-author">
                                    <?php echo $author; ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="blog-text-title">
                        <?php echo $title; ?>
                    </div>
                    <div class="blog-text">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <!-- Footer kısmı -->
    </footer>

</body>

</html>
