<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - Genti Production</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- bootstrap links -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="scripts.js"></script>
  <!-- JavaScript in the head section -->
  <!-- <script>
    // JavaScript in the head section
    console.log("This script is running in the <head> section.");
    const welcomeMessage = "Welcome to Genti Production!";
    alert(welcomeMessage); // Display a welcome message
  </script> -->
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

    <!-- contact -->
    <section class="contact" id="contact">
      <div class="container">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $email = $_POST["email"] ?? '';
          $phone = $_POST["phone"] ?? '';
          $message = $_POST["message"] ?? '';

          $validationErrors = [];

          // Validimi i email-it
          if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
            $validationErrors[] = "❌ Email-i nuk është i vlefshëm.";
          }

          // Validimi i numrit te telefonit
          if (!preg_match("/^\d{9}$/", preg_replace("/^\+383/", "0", preg_replace("/[\s\-]/", "", $phone)))) {
            $validationErrors[] = "❌ Numri i telefonit nuk është i vlefshëm.";
          }

          // Validimi qe mesazhi nuk eshte bosh
          if (empty(trim($message))) {
            $validationErrors[] = "❌ Mesazhi nuk mund të jetë bosh.";
          }

          // Tregon gabimet nese ka te tilla
          if (!empty($validationErrors)) {
            echo "<div style='background:#fee;padding:20px;margin:30px 0;border-radius:5px;'>";
            echo "<h4>Ju lutemi korrigjoni gabimet e mëposhtme:</h4>";
            foreach ($validationErrors as $error) {
              echo "<p>$error</p>";
            }
            echo "</div>";
          } else {
            // Te gjitha validimet jane perfunduar
            echo "<div style='background:#efe;padding:20px;margin:30px 0;border-radius:5px;'>";
            echo "<h4>Faleminderit për mesazhin tuaj!</h4>";
            echo "<p>Mesazhi juaj u dërgua me sukses. Do t'ju kontaktojmë së shpejti.</p>";

            // Formati i numrit nese eshte valid
            $formattedPhone = preg_replace("/(\d{3})(?=\d)/", "$1-", $phone);
            echo "<p>Numri i telefonit i formatuar: <strong>$formattedPhone</strong></p>";

            // Highlight 'kamera' nese eshte ne mesazh
            $highlightedMessage = preg_replace("/kamera/i", "<span style='background:yellow'>KAMERA</span>", $message);
            echo "<p>Mesazhi juaj: <strong>$highlightedMessage</strong></p>";
            echo "</div>";
          }
        }
        ?>
        <div class="row">
          <div class="col-md-7">
            <div class="heading6">Contact <span>Us</span></div>
            <p>Have questions or want to book our services? Get in touch with us today!<br>We'd love to hear from you.
            </p>
            <form id="contactForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
              <!-- Fushat Bazë -->
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input class="form-control" type="text" name="name" id="name" placeholder="Name" aria-label="Name"
                  required>
              </div>
              <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input class="form-control" type="email" name="email" id="email" placeholder="Email" aria-label="Email"
                  required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
              </div>
              <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input class="form-control" type="tel" name="phone" id="phone" placeholder="Phone Number"
                  aria-label="Phone Number" required
                  value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
              </div>

              <div class="form-group">
                <label class="input-label">Preferred Contact Method:</label>
                <div class="form-check">
                  <input class="form-check-input input-radio" type="radio" name="contactMethod" id="emailRadio"
                    value="email" checked>
                  <label class="form-check-label input-label" for="emailRadio">Email</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input input-radio" type="radio" name="contactMethod" id="phoneRadio"
                    value="phone" <?php echo (isset($_POST['contactMethod']) && $_POST['contactMethod'] === 'phone') ? 'checked' : ''; ?>>
                  <label class="form-check-label input-label" for="phoneRadio">Phone</label>
                </div>
              </div>

              <div class="form-group">
                <label class="input-label">Services You're Interested In:</label>
                <div class="form-check">
                  <input class="form-check-input input-checkbox" type="checkbox" name="services[]" id="weddingVideo"
                    value="weddingVideo" <?php echo (isset($_POST['services']) && in_array('weddingVideo', $_POST['services'])) ? 'checked' : ''; ?>>
                  <label class="form-check-label input-label" for="weddingVideo">Wedding Videography</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input input-checkbox" type="checkbox" name="services[]" id="photoShoot"
                    value="photoShoot" <?php echo (isset($_POST['services']) && in_array('photoShoot', $_POST['services'])) ? 'checked' : ''; ?>>
                  <label class="form-check-label input-label" for="photoShoot">Photo Shoot</label>
                </div>
              </div>

              <!-- Mesazhi -->
              <div class="mb-3">
                <label for="message" class="form-label">Your Message</label>
                <textarea class="form-control" name="message" id="message" placeholder="Your Message" rows="3"
                  aria-label="Your Message"
                  required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
              </div>

              <!-- Butoni Submit -->
              <button id="contact-btn" type="submit" name="submit">Send Message</button>
            </form>
          </div>
          <div class="col-md-5" id="col">
            <h1>Info</h1>
            <p><i class="fa-regular fa-envelope"></i> info@gentiproduction.com</p>
            <p><i class="fa-solid fa-phone"></i> +383 44 567 213</p>
            <p><i class="fa-solid fa-map-marker-alt"></i> 3 Heroinat, Prishtine, Kosove</p>
            <p><i class="fa-solid fa-clock"></i> We are available for inquiries and bookings from Monday to Saturday, 9
              AM
              to 5 PM.</p>
          </div>
        </div>
      </div>
    </section>
    <!-- contact -->



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

    <!-- JavaScript in the body section -->
    <script>
      // JavaScript in the body section
      console.log("This script is running in the <body> section.");

      // Variables and Data Types
      const companyName = "Genti Production";
      let yearFounded = 2020;
      const isActive = true;
      const services = ["Wedding Videography", "Photo Shoot", "Event Coverage"];
      const contactInfo = {
        email: "gentiprod@gentiproduction.com",
        phone: "+049******",
        address: "123 Wedding Lane, Prishtina, Kosova"
      };

      // Arrays and Accessing Elements
      console.log("Services:", services);
      console.log("First Service:", services[0]);

      // Objects
      console.log("Contact Info:", contactInfo);
      console.log("Email:", contactInfo.email);

      // Functions with and without parameters
      function greet() {
        console.log("Hello from Genti Production!");
      }

      function greetUser(userName) {
        console.log(`Hello, ${userName}!`);
      }

      greet();
      greetUser("John");

      // Operators
      let a = 10;
      let b = 5;
      console.log("Addition:", a + b);
      console.log("Subtraction:", a - b);
      console.log("Multiplication:", a * b);
      console.log("Division:", a / b);

      // Conditional Statements
      if (yearFounded > 2020) {
        console.log("We are in the future!");
      } else if (yearFounded === 2020) {
        console.log("We were founded in 2020!");
      } else {
        console.log("We are in the past!");
      }

      switch (services.length) {
        case 1:
          console.log("Only one service available.");
          break;
        case 2:
          console.log("Two services available.");
          break;
        default:
          console.log("Multiple services available.");
      }

      // Loops
      for (let i = 0; i < services.length; i++) {
        console.log(`Service ${i + 1}: ${services[i]}`);
      }

      let count = 0;
      while (count < 3) {
        console.log("Count:", count);
        count++;
      }

      // Array Methods: map, filter, reduce
      const serviceLengths = services.map(service => service.length);
      console.log("Service Lengths:", serviceLengths);

      const longServices = services.filter(service => service.length > 10);
      console.log("Long Services:", longServices);

      const totalLength = services.reduce((acc, service) => acc + service.length, 0);
      console.log("Total Length of Service Names:", totalLength);

      // Callback Functions
      function processService(service, callback) {
        console.log("Processing:", service);
        callback(service);
      }

      processService("Wedding Videography", function (service) {
        console.log(`Callback: ${service} is processed.`);
      });

      // Timeout
      setTimeout(() => {
        console.log("This message is displayed after 3 seconds.");
      }, 3000);
    </script>

    <!-- External JavaScript -->
    <script>
      // External JavaScript logic (moved inline for demonstration)
      document.addEventListener("DOMContentLoaded", function () {
        console.log("External script is running after the DOM is fully loaded.");

        // Form submission handling
        document.getElementById('contactForm').addEventListener('submit', function (event) {
          // Client-side validation can be added here if needed
          // The main validation is now handled by PHP
        });

        // Verifikim me regex ne inputin e kerkimit
        $("#searchForm").submit(function (e) {
          const searchText = $("input[type='search']").val();
          const pattern = /^[a-zA-Z0-9\s]+$/;

          if (!pattern.test(searchText)) {
            alert("Search input përmban karaktere të palejuara!");
            e.preventDefault();
          }
        });

      });
    </script>
</body>

</html>