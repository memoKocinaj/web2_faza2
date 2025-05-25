<?php

include_once 'db_connection.php';

// Fetch blogs from the database
$blogs = [];
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Lidhja me databazën dështoi: " . $conn->connect_error);
}
$sql = "SELECT id, title, author, date, image, content FROM blogs ORDER BY date DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $blogs[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blogs - Genti Production</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- bootstrap links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- fonts links -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
</head>
<body>
  <div class="all-content">

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg" id="navbar">
      <div class="container-fluid">
        <a class="navbar-brand" href="#" id="logo"><img src="./images/logo.png" alt="Genti Production"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span><i class="fa-solid fa-bars" style="color: white; font-size: 23px;"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="services.php">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="gallery.php">Gallery</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="blogs.php">Blogs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.php">Contact</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- navbar -->

    <!-- blogs -->
    <section class="blogs" id="blogs" style="margin-top: 50px;">
      <h1>Latest <span>Blogs</span></h1>
      <div class="container">
        <div class="row">
          <?php if (count($blogs) > 0): ?>
            <?php foreach ($blogs as $blog): ?>
              <div class="col-md-4 py-3 py-md-0">
                <div class="card">
                  <img src="<?php echo htmlspecialchars($blog['image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>">
                  <div class="card-body">
                    <h3><?php echo htmlspecialchars($blog['title']); ?></h3>
                    <h5><i><?php echo htmlspecialchars($blog['author']); ?> / <?php echo htmlspecialchars($blog['date']); ?></i></h5>
                    <p><?php echo htmlspecialchars(mb_strimwidth($blog['content'], 0, 100, "...")); ?></p>
                    <button class="btn btn-primary btn-sm" onclick="alert('Lexo më shumë: <?php echo htmlspecialchars(addslashes($blog['title'])); ?>')">Read More</button>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No blog posts found.</p>
          <?php endif; ?>
        </div>
      </div>
    </section>
    <!-- blogs -->

    <script>
      // Verifikim me regex ne inputin e kerkimit
      $("form").submit(function (e) {
        const searchText = $("input[type='search']").val();
        const pattern = /^[a-zA-Z0-9\s]+$/;
        if (!pattern.test(searchText)) {
          alert("Search input përmban karaktere të palejuara!");
          e.preventDefault();
        }
      });
    </script>

    <!-- footer -->
    <footer id="footer">
      <div class="footer-logo text-center">
        <img src="./images/logo.png" alt="Genti Production">
      </div>
      <div class="socail-links text-center">
        <a href="https://twitter.com" target="_blank">
          <i class="fa-brands fa-twitter"></i>
        </a>
        <a href="https://facebook.com" target="_blank">
          <i class="fa-brands fa-facebook-f"></i>
        </a>
        <a href="https://instagram.com" target="_blank">
          <i class="fa-brands fa-instagram"></i>
        </a>
        <a href="https://youtube.com" target="_blank">
          <i class="fa-brands fa-youtube"></i>
        </a>
        <a href="https://pinterest.com" target="_blank">
          <i class="fa-brands fa-pinterest-p"></i>
        </a>
      </div>

      <div class="credite text-center">
        Designed By <a href="#"> Grupi 7</a>
      </div>
      <div class="copyright text-center">
        &copy; Copyright <strong><span>Genti Production</span></strong>. All Rights Reserved
      </div>
    </footer>
    <!-- footer -->

    <a href="#" class="arrow"><i><img src="./images/up-arrow.png" alt="" width="55px"></i></a>
  </div>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
    crossorigin="anonymous"></script>
</body>
</html>