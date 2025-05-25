<?php
session_start(); // Start session to access/set session messages

$host = 'localhost';         // or 127.0.0.1
$db   = 'genti_production';
$user = 'genti_app_user';              // replace with your DB username
$pass = 'genti_app_user';                  // replace with your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Options for better error handling
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Nuk mund të lidhemi me databazën.");
}

// Function to sanitize inputs (you already use this in the main code)
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Function to insert contact form data
function insertContactMessage($name, $email, $phone, $message) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $email, $phone, $message]);
    } catch (PDOException $e) {
        trigger_error("Gabim gjatë ruajtjes në databazë: " . $e->getMessage(), E_USER_ERROR);
        return false;
    }
}



// Include database connection
include_once 'db_connection.php';

// Custom error handler for detailed Albanian error messages
function customContactErrorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
    // Check if error reporting is enabled for this type of error
    if (!(error_reporting() & $errno)) {
        return false;
    }

    $message = "";
    switch ($errno) {
        case E_ERROR:
        case E_USER_ERROR:
            $message = "Gabim Fatal: Ndodhi një gabim i rëndësishëm në server. Ju lutem provoni përsëri më vonë.";
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $message = "Paralajmërim: Diçka nuk shkoi siç duhet, por operacioni mund të vazhdojë.";
            break;
        case E_NOTICE:
        case E_USER_NOTICE:
            $message = "Njoftim: Një problem i vogël u identifikua, por nuk është kritik.";
            break;
        default:
            $message = "Gabim i panjohur: Ndodhi një problem i papritur.";
            break;
    }
    error_log("APP ERROR: [$errno] $errstr në $errfile në rreshtin $errline. Konteksti: " . json_encode($errcontext)); // Log for debugging
    $_SESSION['form_message'] = "<div class='alert alert-danger'>Gabim: " . $message . "</div>";
    header("Location: contact.php");
    exit();
}

set_error_handler("customContactErrorHandler");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

    // Basic server-side validation
    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['form_message'] = "<div class='alert alert-danger'>Ju lutem plotësoni të gjitha fushat e kërkuara (Emri, Email, Mesazhi).</div>";
        header("Location: contact.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['form_message'] = "<div class='alert alert-danger'>Adresa e emailit nuk është e vlefshme.</div>";
        header("Location: contact.php");
        exit();
    }

    // Insert into database using prepared statement
    // The insertContactMessage function is defined in db_connection.php
    $db_success = insertContactMessage($name, $email, $phone, $message);

    if ($db_success) {
        // Attempt to send email
        $to = "mehmed.kocinaj@student.uni-pr.edu"; // veq me provu ktu
        $subject = "Mesazh i ri nga Formulari i Kontaktit";
        $headers = "From: " . $email . "\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_body = "<h2>Mesazh i ri nga Genti Production</h2>";
        $email_body .= "<p><b>Emri:</b> " . $name . "</p>";
        $email_body .= "<p><b>Email:</b> " . $email . "</p>";
        $email_body .= "<p><b>Telefoni:</b> " . (!empty($phone) ? $phone : "Nuk u dha") . "</p>";
        $email_body .= "<p><b>Mesazhi:</b><br>" . nl2br($message) . "</p>";

        // Using mail() function. For Gmail/Yahoo/Hotmail, you typically need SMTP.
        // For local testing, you might need a local mail server (like Sendmail/Postfix on Linux, or MailHog/FakeSMTP for dev).
        // For production, use PHPMailer or SwiftMailer with SMTP.
        $mail_sent = mail($to, $subject, $email_body, $headers);

        if ($mail_sent) {
            $_SESSION['form_message'] = "<div class='alert alert-success'>Mesazhi juaj u dërgua me sukses!</div>";
        } else {
            // This error will be logged by the custom error handler if mail() fails due to server issues
            $_SESSION['form_message'] = "<div class='alert alert-warning'>Mesazhi u ruajt, por dërgimi i emailit dështoi. Ju lutem provoni përsëri më vonë.</div>";
            trigger_error("Dërgimi i emailit dështoi nga formulari i kontaktit.", E_USER_WARNING);
        }
    } else {
        $_SESSION['form_message'] = "<div class='alert alert-danger'>Ndodhi një gabim gjatë ruajtjes së mesazhit tuaj. Ju lutem provoni përsëri.</div>";
        // Error will be caught by customErrorHandler via trigger_error in insertContactMessage
    }

    header("Location: contact.php");
    exit();

} else {
    // If not a POST request, redirect to the contact page
    header("Location: contact.php");
    exit();
}
?>