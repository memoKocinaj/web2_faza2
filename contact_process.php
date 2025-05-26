<?php
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
require 'phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Konfigurimi i databazes
$host = 'localhost';
$db = 'genti_production';
$user = 'genti_app_user';
$pass = 'genti_app_user';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Opsione PDO per errora
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Nuk mund të lidhemi me databazën.");
}

// Sanitizon inputin
function sanitize_input($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Inserton mesazhin e contactit ne databaze
function insertContactMessage($name, $email, $phone, $message)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        return $stmt->execute([$name, $email, $phone, $message]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

// Menaxhimi i errorav
function customContactErrorHandler($errno, $errstr, $errfile, $errline)
{
    $messages = [
        E_ERROR => "Gabim Fatal: Ndodhi një gabim i rëndësishëm në server.",
        E_WARNING => "Paralajmërim: Diçka nuk shkoi siç duhet.",
        E_NOTICE => "Njoftim: Një problem i vogël u identifikua.",
        E_USER_ERROR => "Gabim: ",
        E_USER_WARNING => "Paralajmërim: ",
        E_USER_NOTICE => "Njoftim: "
    ];

    $message = $messages[$errno] ?? "Gabim i panjohur: ";
    $message .= $errstr;

    error_log("Error [$errno] in $errfile on line $errline: $errstr");
    $_SESSION['form_message'] = "<div class='alert alert-danger'>$message</div>";
    header("Location: contact.php");
    exit();
}

set_error_handler("customContactErrorHandler");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validimi i inputev
    $name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['form_message'] = "<div class='alert alert-danger'>Ju lutem plotësoni të gjitha fushat e kërkuara.</div>";
        header("Location: contact.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['form_message'] = "<div class='alert alert-danger'>Email i pavlefshëm.</div>";
        header("Location: contact.php");
        exit();
    }

    // Insertimi ne databaze
    $db_success = insertContactMessage($name, $email, $phone, $message);

    if ($db_success) {
        // Konfigurimi i emailit
        $to_admin = "leartbalidemaj12@gmail.com"; // Admin email
        $to_user = $email; // User's email
        $subject_admin = "Mesazh i ri nga: $name";
        $subject_user = "Faleminderit për mesazhin tuaj";

        // Header i email
        $headers = "From: Genti Production <leartbalidemaj12@gmail.com>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // Contenti i emailit adminit
        $email_body_admin = "
            <html>
            <body>
                <h2 style='color: #002642;'>Mesazh i ri nga kontakt forma</h2>
                <p><strong>Emri:</strong> $name</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Telefoni:</strong> " . ($phone ?: 'Nuk u dha') . "</p>
                <p><strong>Mesazhi:</strong></p>
                <div style='border:1px solid #ddd; padding:10px;'>" . nl2br($message) . "</div>
                <p style='margin-top:20px;'>Ky mesazh u dërgua më " . date('d.m.Y H:i') . "</p>
            </body>
            </html>
        ";

        // Emaili konfirmues per userin
        $email_body_user = "
            <html>
            <body>
                <h2 style='color: #002642;'>Faleminderit për kontaktin!</h2>
                <p>Ne kemi marrë mesazhin tuaj dhe do t'ju përgjigjemi sa më shpejt të jetë e mundur.</p>
                <p><strong>Përmbledhje e mesazhit tuaj:</strong></p>
                <ul>
                    <li><strong>Emri:</strong> $name</li>
                    <li><strong>Email:</strong> $email</li>
                    " . ($phone ? "<li><strong>Telefoni:</strong> $phone</li>" : "") . "
                </ul>
                <p>Për çdo pyetje tjetër, mund të na kontaktoni përsëri.</p>
                <p>Përshëndetje,<br>Ekipi i Genti Production</p>
            </body>
            </html>
        ";

        // Dergimi emailit
        $admin_mail_sent = mail($to_admin, $subject_admin, $email_body_admin, $headers);
        $user_mail_sent = mail($to_user, $subject_user, $email_body_user, $headers);

        if ($admin_mail_sent && $user_mail_sent) {
            $_SESSION['form_message'] = "<div class='alert alert-success'>Mesazhi juaj u dërgua me sukses! Ju do të merrni një email konfirmimi.</div>";
        } elseif ($admin_mail_sent) {
            $_SESSION['form_message'] = "<div class='alert alert-success'>Mesazhi juaj u dërgua me sukses!</div>";
        } else {
            $_SESSION['form_message'] = "<div class='alert alert-warning'>Mesazhi u ruajt, por dërgimi i email-it dështoi.</div>";
        }
    } else {
        $_SESSION['form_message'] = "<div class='alert alert-danger'>Ndodhi një gabim gjatë ruajtjes së mesazhit tuaj.</div>";
    }

    header("Location: contact.php");
    exit();
} else {
    header("Location: contact.php");
    exit();
}
?>