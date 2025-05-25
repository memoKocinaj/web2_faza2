<?php
session_start(); // Start session to access/set session messages

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
    $db_success = insertContactMessage($name, $email, $phone, $message);

    if ($db_success) {
        // ✅ Log message to a file using fopen, fwrite, fclose, and fsize
        $logFile = "contact_messages.txt";
        $fileHandle = fopen($logFile, "a"); // Open for appending
        if ($fileHandle) {
            $logEntry = "[" . date("Y-m-d H:i:s") . "] Emri: $name | Email: $email | Tel: " . (!empty($phone) ? $phone : "Nuk u dha") . " | Mesazhi: $message\n";
            fwrite($fileHandle, $logEntry);
            fclose($fileHandle);

            // Get and log file size (optional usage of fsize)
            $fileSize = filesize($logFile);
            error_log("Skedari 'contact_messages.txt' tani ka madhësinë: $fileSize byte.");
        } else {
            trigger_error("Nuk u arrit të hapet skedari për ruajtje të mesazhit.", E_USER_WARNING);
        }

        // Attempt to send email
        $to = "recipient@example.com"; // !!! REPLACE WITH YOUR RECIPIENT EMAIL (e.g., your email) !!!
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

        $mail_sent = mail($to, $subject, $email_body, $headers);

        if ($mail_sent) {
            $_SESSION['form_message'] = "<div class='alert alert-success'>Mesazhi juaj u dërgua me sukses!</div>";
        } else {
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
