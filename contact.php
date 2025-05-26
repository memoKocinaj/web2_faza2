<!DOCTYPE html>
<html lang="en">
<?php
session_start();

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once 'db_connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize_input($_POST['name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $message = sanitize_input($_POST['message'] ?? '');

    $validationErrors = [];

    if (empty($name)) {
        $validationErrors[] = "❌ Fusha 'Emri' është e detyrueshme.";
    }
    if (empty($email)) {
        $validationErrors[] = "❌ Fusha 'Email' është e detyrueshme.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $validationErrors[] = "❌ Adresa e emailit nuk është e vlefshme.";
    }
    if (empty(trim($message))) {
        $validationErrors[] = "❌ Mesazhi nuk mund të jetë bosh.";
    }
    $cleanedPhone = preg_replace("/[\s\-]/", "", $phone);
    // Validimi i numrit te telefonit
    if (!preg_match("/^\d{9}$/", preg_replace("/^\+383/", "0", preg_replace("/[\s\-]/", "", $phone)))) {
        $validationErrors[] = "❌ Numri i telefonit nuk është i vlefshëm.";
      }

    // Korrigjimi i gabimeve
    if (!empty($validationErrors)) {
        $_SESSION['form_message'] = "<div class='alert alert-danger' style='background:#fee;padding:20px;margin:30px 0;border-radius:5px;'>";
        $_SESSION['form_message'] .= "<h4>Ju lutemi korrigjoni gabimet e mëposhtme:</h4>";
        foreach ($validationErrors as $error) {
            $_SESSION['form_message'] .= "<p>$error</p>";
        }
        $_SESSION['form_message'] .= "</div>";
        $_SESSION['form_data'] = $_POST; // Ruan datan
        header("Location: contact.php");
        exit();
    }

    // Validimet perfundojne, insertimi ne databaze dhe dergimi i emailit
    if (insertContactMessage($name, $email, $phone, $message)) {
        // Ruan mesazhin ne logs
        $logDir = __DIR__ . '/logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/contact_messages.txt';
        $fileHandle = fopen($logFile, "a");
        if ($fileHandle) {
            $logEntry = "[" . date("Y-m-d H:i:s") . "] Emri: $name | Email: $email | Tel: " . ($phone ?: "Nuk u dha") . " | Mesazhi: $message\n";
            fwrite($fileHandle, $logEntry);
            fclose($fileHandle);
        }

        // Dergon email me PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'UTF-8';
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'leartbalidemaj12@gmail.com'; // Emaili i pronarit
            $mail->Password = 'mqep uesu aeul cdxv'; // Passwordi i app (nese 2FA on)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('noreply@gentiproduction.com', 'Genti Production');
            $mail->addAddress('leartbalidemaj12@gmail.com');
            $mail->addReplyTo($email, $name);

            $mail->isHTML(true);
            $mail->Subject = "Mesazh i ri nga Formulari i Kontaktit - Genti Production";

            $email_body = "<html><body>";
            $email_body .= "<h2 style='color: #002642;'>Mesazh i ri nga Genti Production</h2>";
            $email_body .= "<table style='width: 100%; border-collapse: collapse;'>";
            $email_body .= "<tr style='background-color: #f8f9fa;'><td style='padding: 10px; border: 1px solid #ddd;'><strong>Emri:</strong></td><td style='padding: 10px; border: 1px solid #ddd;'>$name</td></tr>";
            $email_body .= "<tr><td style='padding: 10px; border: 1px solid #ddd;'><strong>Email:</strong></td><td style='padding: 10px; border: 1px solid #ddd;'>$email</td></tr>";
            $email_body .= "<tr style='background-color: #f8f9fa;'><td style='padding: 10px; border: 1px solid #ddd;'><strong>Telefoni:</strong></td><td style='padding: 10px; border: 1px solid #ddd;'>" . ($phone ?: "Nuk u dha") . "</td></tr>";
            $email_body .= "<tr><td style='padding: 10px; border: 1px solid #ddd; vertical-align: top;'><strong>Mesazhi:</strong></td><td style='padding: 10px; border: 1px solid #ddd;'>" . nl2br($message) . "</td></tr>";
            $email_body .= "</table>";
            $email_body .= "<p style='margin-top: 20px;'>Ky mesazh u dërgua automatikisht nga formulari i kontaktit në faqen tuaj të internetit.</p>";
            $email_body .= "</body></html>";

            $mail->Body = $email_body;

            $mail->send();

            // Email konfirmues
            $mail->clearAddresses();
            $mail->addAddress($email);
            $mail->Subject = "Faleminderit për mesazhin tuaj - Genti Production";

            $user_email_body = "<html><body>";
            $user_email_body .= "<h2 style='color: #002642;'>Faleminderit për kontaktin!</h2>";
            $user_email_body .= "<p>Ne kemi marrë mesazhin tuaj dhe do t'ju kontaktojmë sa më shpejt të jetë e mundur.</p>";
            $user_email_body .= "<p><strong>Përmbledhje e mesazhit tuaj:</strong></p>";
            $user_email_body .= "<ul>";
            $user_email_body .= "<li><strong>Emri:</strong> $name</li>";
            $user_email_body .= "<li><strong>Email:</strong> $email</li>";
            if ($phone) {
                $user_email_body .= "<li><strong>Telefoni:</strong> $phone</li>";
            }
            $user_email_body .= "</ul>";
            $user_email_body .= "<p>Për çdo pyetje tjetër, mund të na kontaktoni përsëri në këtë email ose në numrin e telefonit të faqes sonë.</p>";
            $user_email_body .= "<p>Përshëndetje,<br>Ekipi i Genti Production</p>";
            $user_email_body .= "</body></html>";

            $mail->Body = $user_email_body;

            $mail->send();

            $_SESSION['form_message'] = "<div class='alert alert-success' style='background:#efe;padding:20px;margin:30px 0;border-radius:5px;'>Mesazhi juaj u dërgua me sukses! Ju do të merrni një email konfirmimi.</div>";
            unset($_SESSION['form_data']);

        } catch (Exception $e) {
            $_SESSION['form_message'] = "<div class='alert alert-danger' style='background:#fee;padding:20px;margin:30px 0;border-radius:5px;'>Mesazhi nuk mund të dërgohet. Gabim: {$mail->ErrorInfo}</div>";
            error_log("Mailer Error: " . $mail->ErrorInfo);
            $_SESSION['form_data'] = $_POST;
        }

    } else {
        $_SESSION['form_message'] = "<div class='alert alert-danger' style='background:#fee;padding:20px;margin:30px 0;border-radius:5px;'>Ndodhi një gabim gjatë ruajtjes së mesazhit tuaj. Ju lutem provoni përsëri.</div>";
        $_SESSION['form_data'] = $_POST;
    }

    header("Location: contact.php");
    exit();
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Genti Production</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="scripts.js"></script>
    </head>

<body>
    <div class="all-content">

        <nav class="navbar navbar-expand-lg" id="navbar">
            <div class="container-fluid">
                <a class="navbar-brand" href="#" id="logo"><img src="./images/logo.png" alt="Genti Production"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
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
        <section class="contact" id="contact">
            <div class="container">
                <?php
                if (isset($_SESSION['form_message'])) {
                    echo $_SESSION['form_message'];
                    unset($_SESSION['form_message']);
                }
                ?>
                <div class="row">
                    <div class="col-md-7">
                        <div class="heading6">Contact <span>Us</span></div>
                        <p>Have questions or want to book our services? Get in touch with us today!<br>We'd love to hear
                            from you.
                        </p>
                        <form id="contactForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                            method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input class="form-control" type="text" name="name" id="name" placeholder="Name"
                                    aria-label="Name" required
                                    value="<?php echo isset($_SESSION['form_data']['name']) ? htmlspecialchars($_SESSION['form_data']['name']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" type="email" name="email" id="email" placeholder="Email"
                                    aria-label="Email" required
                                    value="<?php echo isset($_SESSION['form_data']['email']) ? htmlspecialchars($_SESSION['form_data']['email']) : ''; ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input class="form-control" type="tel" name="phone" id="phone"
                                    placeholder="Phone Number" aria-label="Phone Number"
                                    value="<?php echo isset($_SESSION['form_data']['phone']) ? htmlspecialchars($_SESSION['form_data']['phone']) : ''; ?>">
                            </div>

                            <div class="form-group">
                                <label class="input-label">Preferred Contact Method:</label>
                                <div class="form-check">
                                    <input class="form-check-input input-radio" type="radio" name="contactMethod"
                                        id="emailRadio" value="email"
                                        <?php echo (!isset($_SESSION['form_data']['contactMethod']) || (isset($_SESSION['form_data']['contactMethod']) && $_SESSION['form_data']['contactMethod'] === 'email')) ? 'checked' : ''; ?>>
                                    <label class="form-check-label input-label" for="emailRadio">Email</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input input-radio" type="radio" name="contactMethod"
                                        id="phoneRadio" value="phone"
                                        <?php echo (isset($_SESSION['form_data']['contactMethod']) && $_SESSION['form_data']['contactMethod'] === 'phone') ? 'checked' : ''; ?>>
                                    <label class="form-check-label input-label" for="phoneRadio">Phone</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="input-label">Services You're Interested In:</label>
                                <div class="form-check">
                                    <input class="form-check-input input-checkbox" type="checkbox" name="services[]"
                                        id="weddingVideo" value="weddingVideo"
                                        <?php echo (isset($_SESSION['form_data']['services']) && in_array('weddingVideo', $_SESSION['form_data']['services'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label input-label" for="weddingVideo">Wedding
                                        Videography</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input input-checkbox" type="checkbox" name="services[]"
                                        id="photoShoot" value="photoShoot"
                                        <?php echo (isset($_SESSION['form_data']['services']) && in_array('photoShoot', $_SESSION['form_data']['services'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label input-label" for="photoShoot">Photo Shoot</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Your Message</label>
                                <textarea class="form-control" name="message" id="message" placeholder="Your Message"
                                    rows="3" aria-label="Your Message"
                                    required><?php echo isset($_SESSION['form_data']['message']) ? htmlspecialchars($_SESSION['form_data']['message']) : ''; ?></textarea>
                            </div>

                            <button id="contact-btn" type="submit" name="submit">Send Message</button>
                        </form>
                    </div>
                    <div class="col-md-5" id="col">
                        <h1>Info</h1>
                        <p><i class="fa-regular fa-envelope"></i> info@gentiproduction.com</p>
                        <p><i class="fa-solid fa-phone"></i> +383 44 567 213</p>
                        <p><i class="fa-solid fa-map-marker-alt"></i> 3 Heroinat, Prishtine, Kosove</p>
                        <p><i class="fa-solid fa-clock"></i> We are available for inquiries and bookings from Monday to
                            Saturday, 9
                            AM
                            to 5 PM.</p>
                    </div>
                </div>
            </div>
        </section>
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
                © Copyright <strong><span>Genti Production</span></strong>. All Rights Reserved
            </div>
        </footer>
        <a href="#" class="arrow"><i><img src="./images/up-arrow.png" alt="" width="55px"></i></a>

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

        <script>
            // External JavaScript logic (moved inline for demonstration)
            document.addEventListener("DOMContentLoaded", function () {
                console.log("External script is running after the DOM is fully loaded.");

                // Form submission handling
                document.getElementById('contactForm').addEventListener('submit', function (event) {
                    // Client-side validation can be added here if needed
                    // The main validation is now handled by PHP
                });
            });
        </script>
</body>

</html>