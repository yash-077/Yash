<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
    $mobile = filter_var(trim($_POST['mobile']), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    // Check if fields are empty
    if (empty($name) || empty($email) || empty($subject) || empty($mobile) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // API URL and API Key from MailerSend
    $api_url = "https://api.mailersend.com/v1/email";
    $api_key = "mlsn.19cf920d89790ccbf6d78f50ccedd818202ae28a83712e091806c60acb4aa100"; // Replace with your actual MailerSend API key

    // Prepare email data
    $email_data = [
        "from" => [
            "email" => $email, // Using the email from the form
            "name" => $name
        ],
        "to" => [
            [
                "email" => "devilyash366@gmail.com", // Replace with your email where you'll receive the form data
                "name" => "Yash"
            ]
        ],
        "subject" => $subject,
        "html" => "
            <p>You have received a new message from your contact form.</p>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Mobile:</strong> $mobile</p>
            <p><strong>Message:</strong><br>$message</p>"
    ];

    // Prepare headers
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ];

    // Initialize cURL session to send email via MailerSend
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($email_data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Execute cURL and check for success or error
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Handle the response
    if ($http_code == 202) {  // 202 means the request was accepted
        echo json_encode(["status" => "success", "message" => "Email sent successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send email."]);
    }
}
?>
