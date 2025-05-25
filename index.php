<!DOCTYPE html>
<html lang="en">
<?php
session_start();

//Krijimi i cookie
if (isset($_GET['change_logo'])) {
  setcookie("logo", "new-logo.png", time() + (86400 * 30), "/");
  header("Location: index.php");
  exit;
}

// Fshirja e cookiet te logo-s
if (isset($_GET['delete_logo_cookie'])) {
  setcookie("logo", "", time() - 3600, "/");
  header("Location: index.php");
  exit;
}

// Leximi i cookie
$logo = isset($_COOKIE['logo']) ? $_COOKIE['logo'] : "logo.png";

// Testimi i user_data cookie
$userData = array('name' => 'Festa', 'role' => 'influencere');
setcookie("user_data", json_encode($userData), time() + (86400 * 30), "/");

// Numerimi i vizitave ne faqe
if (!isset($_SESSION['numri_vizitave'])) {
  $_SESSION['numri_vizitave'] = 1;
} else {
  $_SESSION['numri_vizitave']++;
}

// Marrja e emrit nga perdoruesi
if (isset($_POST['submit_emri'])) {
  $_SESSION['emri'] = htmlspecialchars($_POST['emri']);
}

// Manipulim me vlera ne sesion
if (!isset($_SESSION['status'])) {
  $_SESSION['status'] = "Aktiv";
}

class WebsiteInfo
{
  protected $title = "Genti Production";

  protected function getTitle()
  {
    return $this->title;
  }

  public function displayTitle()
  {
    return $this->getTitle();
  }
}

$site = new WebsiteInfo();
?>

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $site->displayTitle(); ?></title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <!-- bootstrap links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- jQuery CDN -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    .code-container {
      background-color: #f8f9fa;
      border-radius: 5px;
      border-left: 4px solid #002642;
      font-family: 'Courier New', monospace;
    }

    .card {
      border: none;
      border-radius: 10px;
      overflow: hidden;
    }

    pre {
      white-space: pre-wrap;
      word-wrap: break-word;
      margin-bottom: 0;
      padding: 0;
    }

    .badge {
      font-size: 0.9em;
      padding: 0.5em 0.75em;
    }

    .badge.bg-primary {
      background-color: #002642 !important;
    }
  </style>
</head>

<body>

  <div class="all-content">

    <!-- navbar -->
    <nav class="navbar navbar-expand-lg" id="navbar">
      <div class="container-fluid">
        <a class="navbar-brand" href="#" id="logo">
          <img src="./images/<?php echo $logo; ?>" alt="Genti Production">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span><i class="fa-solid fa-bars" style="color: white; font-size: 23px;"></i></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#">Home</a>
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

    <!-- home section -->
    <section id="home">
      <div class="content text-center">
        <h3 class="mx-auto">Capture Your Special Moments <br> With Genti Production</h3>
        <p class="mx-auto">We specialize in wedding photography and videography, capturing the essence of your special
          day.
          <br>Let us tell your story through our lens.
        </p>
        <button id="btn"><a class="nav-link" href="contact.php">Book Now</a></button>
      </div>
    </section>
    <!-- home section -->

    <!-- HTML5 Audio and Video Example -->
    <section id="multimedia">
      <div class="container">
        <h2>Multimedia</h2>
        <audio controls>
          <source src="song.mp3" type="audio/mpeg">
          Your browser does not support the audio element.
        </audio>
        <video width="900" height="600" controls>
          <source src="wed.mp4" type="video/mp4">
          Your browser does not support the video tag.
        </video>
      </div>
    </section>

    <div class="text-center mt-4">
      <a href="?change_logo=true" class="btn btn-warning">Ndrysho Logon</a>
      <a href="?delete_logo_cookie=true" class="btn btn-danger ms-2">Fshij Cookie-n e Logos</a>
    </div>

    <div class="container text-center mt-4">
      <h5>
        Vizita: <?php echo $_SESSION['numri_vizitave']; ?><br>
        <?php if (isset($_SESSION['emri'])): ?>
          Emri: <?php echo htmlspecialchars($_SESSION['emri']); ?><br>
        <?php else: ?>
          Emri: Nuk është vendosur<br>
        <?php endif; ?>
        Statusi: <?php echo $_SESSION['status']; ?>
      </h5>

      <form method="post" class="mt-3">
        <div class="mb-3">
          <label for="emri" class="form-label">Shkruani emrin tuaj:</label>
          <input type="text" class="form-control" id="emri" name="emri" required
            value="<?php echo isset($_SESSION['emri']) ? htmlspecialchars($_SESSION['emri']) : ''; ?>">
        </div>
        <button type="submit" name="submit_emri" class="btn btn-primary" style="background-color: #002642;">Ruaj
          Emrin</button>
      </form>
    </div>

    <!-- Testimi i Cookies -->

    <div class="container mt-5">
      <div class="card shadow-sm">
        <div class="card-header" style="background-color: #002642;">
          <h3 class="text-center mb-0 text-white">Testimi i Cookies</h3>
        </div>
        <div class="card-body">
          <?php
          if (isset($_COOKIE['user_data'])) {
            $decodedData = json_decode($_COOKIE['user_data'], true);

            echo "<div class='code-container p-3 mb-4'>";
            echo "<pre>";
            print_r($decodedData);
            echo "</pre>";
            echo "</div>";

            if (is_array($decodedData)) {
              echo "<div class='alert alert-success'>";
              echo "<strong>Cookie u lexua me sukses!</strong><br>";
              echo "<span class='badge bg-primary me-2'>Name: " . htmlspecialchars($decodedData['name']) . "</span>";
              echo "<span class='badge bg-secondary'>Role: " . htmlspecialchars($decodedData['role']) . "</span>";
              echo "</div>";
            }
          } else {
            echo "<div class='alert alert-warning'>Nuk u gjet asnjë cookie e të dhënave të përdoruesit</div>";
          }
          ?>
        </div>
      </div>
    </div>

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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>
  <!-- JavaScript -->
  <script>
    $(document).ready(function () {
      // 1. Perdorimi i jQuery selektoreve dhe ngjarjeve
      $("#btn").click(function () {
        alert("Button clicked!");
      });

      // 2. Perdorimi i jQuery efekteve (Hide, Show)
      $("h2").click(function () {
        $(this).next().toggle("slow");
      });

      // 3. Perdorimi i jQuery efekteve (Fade, Slide, animate)
      $(".socail-links i").hover(
        function () {
          $(this).fadeTo("fast", 0.5);
        },
        function () {
          $(this).fadeTo("fast", 1);
        }
      );

      // 4. Perdorimi i jQuery efekteve (Callback)
      $(".arrow").click(function () {
        $("html, body").animate({ scrollTop: 0 }, "slow", function () {
          alert("You have reached the top of the page!");
        });
      });

      // 5. Perdorimi i jQuery me HTML (Get, Set, Add, Remove)
      $("#btn").click(function () {
        // Merr tekstin e butonit
        const buttonText = $(this).text();
        console.log("Button Text:", buttonText);

        // Ndrysho tekstin e butonit
        $(this).text("Loading...");

        // Shto nje klase te re
        $(this).addClass("btn-loading");

        // Hiq nje element
        setTimeout(function () {
          $(".credite").remove();
        }, 3000);
      });

      // Shtese: Manipulim i HTML me jQuery
      // Shto nje paragraf te ri ne fund te seksionit 'home'
      $("#btn").click(function () {
        $("#home .content").append("<p>New paragraph added with jQuery!</p>");
      });

      // Ndrysho atributin 'src' te logos në navbar
      $("#btn").click(function () {
        $("#logo img").attr("src", "./images/new-logo.png");
      });

      // Shto nje klase ne footer
      $("#btn").click(function () {
        $("#footer").addClass("footer-highlight");
      });

      // Verifikim me regex ne inputin e kerkimit
      //$("form").submit(function (e) {
      // const searchText = $("input[type='search']").val();
      // const pattern = /^[a-zA-Z0-9\s]+$/;

      // if (!pattern.test(searchText)) {
      //   alert("Search input përmban karaktere të palejuara!");
      //   e.preventDefault();
      // }
      // });
    });
  </script>
</body>

</html>