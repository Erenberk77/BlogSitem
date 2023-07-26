<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog</title>

    <link rel="shortcut icon" href="assets/img/icon/favicon.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assets/fonts/flaticon/flaticon.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/mobile.css">
</head>

<body>

    <header>
        <div class="container">
            <div class="header-wrapper mt-5">
                <div class="row header-content">
                    <div class="header-title col-md-8">
                        <a href="index.html">Blog Title</a>
                    </div>
                    <div class="header-menu col-md-4">
                        <ul>
                            <li>
                                <a href="index.html">Anasayfa</a>
                            </li>
                            <li>
                                <a href="blog.html" style="opacity: 100%;">Blog</a>
                            </li>
                            <li>
                                <a href="about.html">Hakkında</a>
                            </li>
                            <li>
                                <a href="contact.html">İletişim</a>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="blog-post-wrapper">
        <div class="container mt-4">
            <div class="blog-post-container">
                <div class="blog-post-top-title">
                    Blog
                </div>
                <div class="blog-post-row">
                    <div class="row">
                        <?php
                        // Veritabanı bağlantısı için ayarlar
                        $db = new SQLite3('database.sqlite');

                        // Blog gönderilerini veritabanından çek
                        $query = "SELECT * FROM blog_posts ORDER BY date DESC";
                        $result = $db->query($query);

                        while ($row = $result->fetchArray(SQLITE3_ASSOC)):
                        ?>
                        <div class="blog-post col-md-6">
                            <a href="blog-text.php?id=<?php echo $row["id"]; ?>">
                                <div class="blog-post-thumbnail">
                                    <!-- Burada resmi göstermek için gerekli kodları ekleyebilirsiniz -->
                                    <!-- Örneğin: <img src="<?php echo $row["thumbnail"]; ?>" alt="Thumbnail"> -->
                                </div>
                                <div class="blog-post-text">
                                    <div class="blog-post-title">
                                        <?php echo $row["title"]; ?>
                                    </div>
                                    <div class="blog-post-description">
                                        <?php echo $row["content"]; ?>
                                    </div>
                                    <div class="blog-post-meta-info">
                                        <ul>
                                            <li>
                                                <div class="blog-post-date">
                                                    <?php echo $row["date"]; ?>
                                                </div>
                                                <!-- Diğer gönderi meta bilgilerini de burada gösterebilirsiniz -->
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <div class="container-fluid mt-5"></div>
        <hr>
        </div>
        <div class="container text-center mt-5 mb-5">
            <div class="copyright">
                © 2023 Tüm hakları saklıdır.
            </div>
        </div>

    </footer>

</body>

</html>
