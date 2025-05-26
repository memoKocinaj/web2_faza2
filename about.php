<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us - Genti Production</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- bootstrap links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
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
  // Konstanta dhe variabla
  define("Studio_Name", "Genti Production");
  $foundationYear = 2010;
  $currentYear = date('Y');
  $yearsActive = $currentYear - $foundationYear;

  function getTeamCount()
  {
    return 12;
  }

  // Varg asociativ me sherbime
  $services = [
    "Photography" => "Wedding, Portrait, Commercial",
    "Videography" => "Events, Promotional, Documentary",
    "Editing" => "Photo Editing, Video Editing"
  ];

  // Manipulime numerike
  $teamCount = getTeamCount();
  $averageTeamPerService = $teamCount / count($services);
  $editingTeamRatio = 3 / $teamCount * 100;
  $doubleFoundation = $foundationYear * 2;

  // RegEx per perpunim te permbajtjes se stringjeve
  $keywords = [];
  foreach ($services as $key => $value) {
    $value = preg_replace("/,/", " |", $value);
    $services[$key] = $value;
    preg_match_all("/\b[A-Z][a-z]+\b/", $value, $matches);
    $keywords[$key] = implode(", ", $matches[0]);
  }


  ?>
<?php
// === Kodi për Demonstrim të Pointerëve dhe Referencave ===
$demoResults = []; // Ruaj rezultatet e demonstruara

// 1. Funksioni me referencë (shtohet në fund të pjesës PHP ekzistuese)
if (isset($_GET['action']) && $_GET['action'] == 'reference_demo1') {
    function modifyTeamCount(&$team) {
        $team += 2;
    }
    modifyTeamCount($teamCount);
    $demoResults['demo1'] = "Team Count u rrit në: $teamCount (nga referenca)";
}

// 2. Vargu me reference
if (isset($_GET['action']) && $_GET['action'] == 'reference_demo2') {
    // Krijo një kopje të vargut $services me referenca
    $modifiedServices = [];
    foreach ($services as $key => &$value) {
        $newKey = "GP-" . $key; // Shto prefiksin "GP-" te çdo emër shërbimi
        $modifiedServices[$newKey] = $value; 
    }
    // Zëvendëso vargun origjinal $services me të modifikuarin
    $services = $modifiedServices;
    
    // Përditëso edhe keywords bazuar në $services të rinj
    $keywords = [];
    foreach ($services as $key => $value) {
        $value = preg_replace("/,/", " | ", $value);
        preg_match_all("/\b[A-Z][a-z]+\b/", $value, $matches);
        $keywords[$key] = implode(", ", $matches[0]);
    }
    
    $demoResults['demo2'] = "Emrat e shërbimeve u modifikuan: " . implode(", ", array_keys($services));
}


// 3. Kthimi i referencës
if (isset($_GET['action']) && $_GET['action'] == 'reference_demo3') {
    function &getYearsActiveRef() {
        global $yearsActive;
        return $yearsActive;
    }
    $ref = &getYearsActiveRef();
    $ref = 20; // Modifikon direkt $yearsActive
    $demoResults['demo3'] = "Years Active u ndryshua në: $yearsActive";
}


// 4. Largimi i referencës 
// 4. Largimi i referencës nga vargu (demo e modifikuar)
if (isset($_GET['action']) && $_GET['action'] == 'reference_demo4') {
    // Krijo një varg dhe referencë si në demo2
    $services = ["Photography", "Videography", "Editing"];
    
    // Krijo referencë për çdo element (si në demo2)
    foreach ($services as &$service) {
        $service = "GP-" . $service;
    }
    
    // Ruaj gjendjen para largimit të referencës
    $beforeUnset = "Para unset(): " . implode(", ", $services);
    
    // Largo referencën e fundit nga cikli foreach
    unset($service); // Largon referencën e mbetur nga cikli
    
    // Ndrysho variablën origjinale (nëse referenca ekzistonte)
    $service = "TEST"; // Kjo nuk do të ndikojë në varg tani
    
    // Ruaj gjendjen pas largimit
    $afterUnset = "Pas unset(): " . implode(", ", $services);
    
    $demoResults['demo4'] = 
        "<strong>Largimi i referencës nga vargu:</strong><br>" .
        "$beforeUnset<br>" .
        "$afterUnset<br>" .
        "Referenca është larguar dhe ndryshimi i \$service nuk ndikon në varg.";
}


// === Kodi për SQL Injection Demo ===
$searchResult = "";
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $cleanSearch = htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8');
    $searchResult = "Kërkimi i sigurt: '$cleanSearch' (do të përdoret në query).";
}
?>


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

    <!-- about section -->
    <div class="about" id="about">
      <div class="container">
        <div class="heading">About <span>Us</span></div>
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <img src="./images/about.jpg" alt="About Genti Production">
            </div>
          </div>
          <div class="col-md-6">
            <h3>What Makes Us Special?</h3>
            <p>At <mark>Genti Production</mark>, we believe that every wedding is unique and deserves to be captured in
              a way that reflects its individuality. Our team of professional photographers and videographers are
              dedicated to creating timeless memories that you will cherish forever.
              <br><br>We specialize in capturing the emotions, details, and moments that make your wedding day truly
              special. From the first look to the last dance, we are there to document every moment.
              <br><br>Our goal is to provide you with stunning visuals that tell the story of your love and commitment.
            </p>
            <hr>
            <h4>Studio Details</h4>
            <table class="table table-bordered">
              <tr>
                <th>Studio Name</th>
                <td><?php echo Studio_Name; ?></td>
              </tr>
              <tr>
                <th>Years Active</th>
                <td><?php echo $yearsActive; ?> years</td>
              </tr>
              <tr>
                <th>Team Members</th>
                <td><?php echo $teamCount; ?> total</td>
              </tr>
              <tr>
                <th>Average Team per Service</th>
                <td><?php echo round($averageTeamPerService, 2); ?></td>
              </tr>
              <tr>
                <th>Editing Team in %</th>
                <td><?php echo round($editingTeamRatio, 1); ?>%</td>
              </tr>

            </table>

            <h5>Service Keywords (Regex extracted)</h5>
            <ul>
              <?php foreach ($keywords as $service => $words): ?>
                <li><strong><?php echo $service; ?>:</strong> <?php echo $words; ?></li>
              <?php endforeach; ?>
            </ul>
            <button id="about-btn">Learn More</button>
          </div>
        </div>
      </div>
    </div>
    <!-- about section -->
<!-- Seksioni i ri për Demonstrim -->
<div class="advanced-demos mt-5" id="demos">
  <div class="container">
    <h3 class="mb-4">PHP Advanced Demos</h3>

    <!-- Demo 1: Referencat në Funksione -->
    <div class="card mb-3">
      <div class="card-body">
        <h5>1. Modifikimi i Variablave përmes Referencës</h5>
        <p>Kliko për të rritur numrin e stafit me 2.</p>
        <a href="about.php?action=reference_demo1#demos" class="btn btn-primary">Ekzekuto</a>
        <?php if (!empty($demoResults['demo1'])): ?>
          <div class="mt-2 text-success"><?php echo $demoResults['demo1']; ?></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Demo 2: Vargu me Reference -->
    <div class="card mb-3">
      <div class="card-body">
        <h5>2. Vargu me Reference</h5>
        <p>Kliko për të modifikuar emrat e shërbimeve.</p>
        <a href="about.php?action=reference_demo2#demos" class="btn btn-primary">Ekzekuto</a>
        <?php if (!empty($demoResults['demo2'])): ?>
          <div class="mt-2 text-success"><?php echo $demoResults['demo2']; ?></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Demo 3: Kthimi i Referencës -->
    <div class="card mb-3">
      <div class="card-body">
        <h5>3. Ndryshimi i Variablës përmes Kthimit të Referencës</h5>
        <p>Kliko për të ndryshuar vitet aktive.</p>
        <a href="about.php?action=reference_demo3#demos" class="btn btn-primary">Ekzekuto</a>
        <?php if (!empty($demoResults['demo3'])): ?>
          <div class="mt-2 text-success"><?php echo $demoResults['demo3']; ?></div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Demo 4: Largimi i Referencës -->
    <div class="card mb-3">
      <div class="card-body">
        <h5>4. Largimi i Referencës me unset()</h5>
        <p>Kliko për të shikuar efektin e unset().</p>
        <a href="about.php?action=reference_demo4#demos" class="btn btn-primary">Ekzekuto</a>
        <?php if (!empty($demoResults['demo4'])): ?>
          <div class="mt-2 text-success"><?php echo $demoResults['demo4']; ?></div>
        <?php endif; ?>
      </div>
    </div>
    

<!-- SQL Injection Demo -->
<div class="card mb-3">
  <div class="card-body">
    <h5 class="text-danger">SQL Injection Demo</h5>
    
    <form method="GET">
      <div class="mb-3">
        <label>Shkruaj emrin e përdoruesit:</label>
        <input type="text" name="username" class="form-control" placeholder="P.sh.: ' OR '1'='1">
      </div>
      <button type="submit" name="unsafe" class="btn btn-danger">Testo Pa Mbrojtje</button>
      <button type="submit" name="safe" class="btn btn-success">Testo Me Mbrojtje</button>
    </form>

    <?php
    if (isset($_GET['username'])) {
      $username = $_GET['username'];
      
      // ======== KODI I PASIGURT ======== 
      if (isset($_GET['unsafe'])) {
        try {
          $query = "SELECT * FROM users WHERE username = '$username'";
          $stmt = $pdo->query($query);
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          die("Gabim: " . $e->getMessage());
        }
      }
      
      // ======== KODI I SIGURT ======== 
      elseif (isset($_GET['safe'])) {
        try {
          $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
          $stmt->bindParam(':username', $username, PDO::PARAM_STR);
          $stmt->execute();
          $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          die("Gabim: " . $e->getMessage());
        }
      }

      // Shfaq rezultatet
      echo "<div class='mt-3'>";
      if (empty($results)) {
        echo "<div class='text-danger'>Nuk u gjet asnjë përdorues!</div>";
      } else {
        echo "<h6>Rezultatet:</h6>";
        echo "<table class='table table-bordered'><tr><th>ID</th><th>Emri</th><th>Email</th></tr>";
        foreach ($results as $row) {
          echo "<tr>";
          echo "<td>" . htmlspecialchars($row['id']) . "</td>";
          echo "<td>" . htmlspecialchars($row['username']) . "</td>";
          echo "<td>" . htmlspecialchars($row['email']) . "</td>";
          echo "</tr>";
        }
        echo "</table>";
      }
      echo "<div class='text-muted'>Input i sanitizuar: " . htmlspecialchars($username) . "</div>";
      echo "</div>";
    }
    ?>
  </div>
</div>
  
     <!-- footer -->
<footer id="footer" class="container-fluid p-0">
  <div class="container-fluid">
    <div class="footer-logo text-center pt-4">
      <img src="./images/logo.png" alt="Genti Production">
    </div>
    <div class="socail-links text-center py-3">
      <a href="https://twitter.com" target="_blank"><i class="fa-brands fa-twitter mx-2"></i></a>
      <a href="https://facebook.com" target="_blank"><i class="fa-brands fa-facebook-f mx-2"></i></a>
      <a href="https://instagram.com" target="_blank"><i class="fa-brands fa-instagram mx-2"></i></a>
      <a href="https://youtube.com" target="_blank"><i class="fa-brands fa-youtube mx-2"></i></a>
      <a href="https://pinterest.com" target="_blank"><i class="fa-brands fa-pinterest-p mx-2"></i></a>
    </div>

     <div class="credite text-center">
        Designed By <a href="#"> Grupi 7</a>
      </div>
      <div class=" text-center">
         <strong><span>Genti Production</span></strong>
      </div>
    </footer>
</footer>
<!-- footer -->
 <style>
 
#footer {
  width: 100vw;
  margin-left: calc(-50vw + 50%);
  background: #002642;
  color: white;
}

.all-content {
  overflow-x: hidden;
}

.footer-logo img {
  max-width: 180px;
  height: auto;
}

.socail-links a {
  font-size: 1.5rem;
  color: white;
  transition: 0.3s;
}

.socail-links a:hover {
  color: #002642;
}

 </style>

    <a href="#" class="arrow"><i><img src="./images/up-arrow.png" alt="" width="55px"></i></a>
    <script>
      document.getElementById("about-btn").onclick = function () {
        window.location.href = "services.php";
      };

      // Verifikim me regex ne inputin e kerkimit
   // Valido VETËM formën e kërkimit në navbar (me ID 'searchForm')
$("#searchForm").submit(function (e) {
  const searchText = $(this).find("input[type='search']").val();
  const pattern = /^[a-zA-Z0-9\sçëÇË@._-]+$/; // Lejon shkronja, numra, hapësira dhe karaktere speciale

  if (!pattern.test(searchText)) {
    alert("Search input përmban karaktere të palejuara!");
    e.preventDefault();
  }
});
    </script>


</body>

</html>

