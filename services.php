<!DOCTYPE html>
<html lang="en">

<?php
  // Lidhja me MySQL (PDO)
$host = 'localhost';
$dbname = 'genti_production_db';
$user = 'root';    // Përdor "root" nëse nuk ke fjalëkalim
$pass = '';        // Lër bosh nëse nuk ka fjalëkalim

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Gabim në lidhje: " . $e->getMessage());
}
?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services - Genti Production</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- bootstrap links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <style>
    .custom-table {
      width: 97%;
      border-collapse: separate;
      border-spacing: 10px;
      border: 2px solid #002642;
      border-radius: 15px;
      overflow: hidden;
      background-color: #f9f9f9;
      margin: 0 20px;
    }

    .custom-table th,
    .custom-table td {
      padding: 15px;
      text-align: left;
      border: 1px solid #ddd;
    }

    .custom-table th {
      background-color: #002642;
      color: white;
      border-radius: 10px 10px 0 0;
    }

    .custom-table td {
      background-color: #ffffff;
    }

    .custom-table tr:hover td {
      background-color: #f1f1f1;
    }
  </style>
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

    <!-- services section -->
    <section class="services" id="services">
      <div class="container">
        <div class="heading3">Our <span>Services</span></div>
      </div>
      <div class="container" id="container2">
        <div class="row">
          <div class="col-md-3 py-3 py-md-0">
            <div class="card">
              <img src="./images/photography.jpg" alt="Wedding Photography">
              <div class="card-body">
                <h3>Wedding Photography</h3>
                <p>We capture every moment of your special day with stunning photography.</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 py-3 py-md-0">
            <div class="card">
              <video controls>
                <source src="wed1.mp4" type="video/mp4">
                Your browser does not support the video tag.
              </video>
              <div class="card-body">
                <h3>Wedding Videography</h3>
                <p>Our videographers create cinematic wedding films that tell your love story.</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 py-3 py-md-0">
            <div class="card">
              <img src="./images/eng.jpg" alt="Engagement Shoots">
              <div class="card-body">
                <h3>Engagement Shoots</h3>
                <p>Capture the excitement of your engagement with a personalized photo session.</p>
              </div>
            </div>
          </div>
          <div class="col-md-3 py-3 py-md-0">
            <div class="card">
              <img src="./images/drone.jpg" alt="Drone Coverage">
              <div class="card-body">
                <h3>Drone Coverage</h3>
                <p>Add a unique perspective to your wedding with aerial drone footage.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <?php
    define('COMPANY_NAME', 'Genti Production');
    class CameraPackage
    {
      private $name;
      private $price;
      private $contents;

      public function __construct($name, $price, $contents)
      {
        $this->name = $name;
        $this->price = $price;
        $this->contents = $contents;
      }

      public function getName()
      {
        return $this->name;
      }

      public function getPrice()
      {
        return $this->price;
      }

      public function getContents()
      {
        return $this->contents;
      }

      public function setName($name)
      {
        $this->name = $name;
      }

      public function setPrice($price)
      {
        $this->price = $price;
      }

      public function setContents($contents)
      {
        $this->contents = $contents;
      }

      public function displayInfo()
      {
        return "<tr>
            <td>{$this->price}€</td>
            <td>{$this->name}</td>
            <td>{$this->contents}</td>
        </tr>";
      }
    }

    class WeddingPackage extends CameraPackage
    {
      private $duration;

      public function __construct($name, $price, $contents, $duration)
      {
        parent::__construct($name, $price, $contents);
        $this->duration = $duration;
      }

      public function getDuration()
      {
        return $this->duration;
      }

      public function setDuration($duration)
      {
        $this->duration = $duration;
      }

      public function displayInfo()
      {
        return parent::displayInfo();
      }
    }

    $packages = [
      new CameraPackage(
        "Basic Video Package",
        300,
        "1 Mirrorless Camera<br>1 Standard Lens<br>1 Tripod<br>4K Video Recording"
      ),

      new CameraPackage(
        "Pro Video Package",
        600,
        "1 Cinema Camera<br>1 Zoom Lens<br>1 Ronin Gimbal<br>1 Drone (DJI Air 3)<br>Slow Motion Support"
      ),

      new CameraPackage(
        "Live Streaming Kit",
        450,
        "1 Sony A7 IV<br>1 24-70mm Lens<br>1 Speakers<br>1 Audio Mixer<br>1 LED Light Panel"
      ),

      new CameraPackage(
        "Documentary Package",
        800,
        "2 Cinema Cameras<br>1 Ronin Gimbal<br>1 Wireless Mic System<br>1 LED Light Kit<br>1 Field Monitor"
      ),
    ];

    $sortType = $_POST['sort'] ?? 'price_asc';

    usort($packages, function ($a, $b) use ($sortType) {
      switch ($sortType) {
        case 'price_asc':
          return $a->getPrice() <=> $b->getPrice();
        case 'price_desc':
          return $b->getPrice() <=> $a->getPrice();
        case 'name_asc':
          return strcmp($a->getName(), $b->getName());
        case 'name_desc':
          return strcmp($b->getName(), $a->getName());
        default:
          return 0;
      }
    });

    // Validimi i email-it
    $email = "info@gentiproduction.com";
    $emailPattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    $isEmailValid = preg_match($emailPattern, $email);
    ?>

    <h2 align="center">Camera Packages - <?php echo COMPANY_NAME; ?></h2>

    <?php if ($isEmailValid): ?>
      <p style="text-align: center;">Contact us at: <?php echo htmlspecialchars($email); ?></p>
    <?php endif; ?>

    <!-- Sorting form -->
    <form method="post" class="sorting-form" style="text-align: center; margin-bottom: 20px;">
      <label for="sort" class="input-label">Sort packages by:</label>
      <select name="sort" id="sort" class="input-field" style="max-width: 300px;">
        <option value="price_asc" <?php if ($sortType == 'price_asc')
          echo 'selected'; ?>>Price (Low to High)</option>
        <option value="price_desc" <?php if ($sortType == 'price_desc')
          echo 'selected'; ?>>Price (High to Low)</option>
        <option value="name_asc" <?php if ($sortType == 'name_asc')
          echo 'selected'; ?>>Name (A-Z)</option>
        <option value="name_desc" <?php if ($sortType == 'name_desc')
          echo 'selected'; ?>>Name (Z-A)</option>
      </select>
      <button type="submit" id="blogbtn">Sort Packages</button>
    </form>

    <!-- Table Output -->
    <div class="table-container">
      <table class="custom-table">
        <thead>
          <tr>
            <th>Price</th>
            <th>Package</th>
            <th>Contents</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($packages as $package): ?>
            <?php echo $package->displayInfo(); ?>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- String functions demonstration -->
    <?php
    $company = "   Genti Production   ";
    ?>
    <div class="container my-5">
      <div class="card text-center shadow">
        <div class="card-header bg-dark text-white">
          String Functions Demonstration
        </div>
        <div class="card-body">
          <p class="card-text">Original: "<strong><?php echo $company; ?></strong>"</p>
          <p class="card-text">Trimmed: "<strong><?php echo trim($company); ?></strong>"</p>
          <p class="card-text">Uppercase: "<strong><?php echo strtoupper($company); ?></strong>"</p>
          <p class="card-text">Lowercase: "<strong><?php echo strtolower($company); ?></strong>"</p>
          <p class="card-text">Word count: <strong><?php echo str_word_count(trim($company)); ?></strong></p>
        </div>
      </div>
    </div>

    <script>
      $("form").submit(function (e) {
        const searchText = $("input[type='search']").val();
        // Nese nuk ka kerkim, lejo dergimin e formularit
        if (searchText === "" || /^[a-zA-Z0-9\s]+$/.test(searchText)) {
          return true;
        } else {
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

</body>

</html>