<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $businessName   = $_POST["business_name"];
    $address        = $_POST["address"];
    $contactPerson  = $_POST["contact_person"];
    $email          = $_POST["email"];
    $phone          = $_POST["phone"];
    $businessType   = $_POST["business_type"];
    $website        = $_POST["website"];
    $state          = $_POST["state"];
    $country        = $_POST["country"];
    $nationality    = $_POST["nationality"];

    // ✉️ Admin (your) email
    $adminEmail = "srdtechnologiesindia@gmail.com"; // ✅ Replace with your GoDaddy email

    // ✅ Email to user
    $toUser = $email;
    $subjectUser = "Business Registration Successful";
    $messageUser = "Hello $contactPerson,\n\nThank you for registering your business \"$businessName\" with us.\n\nDetails:\n"
        . "Business Name: $businessName\n"
        . "Address: $address\n"
        . "Phone: $phone\n"
        . "Business Type: $businessType\n"
        . "Website: $website\n"
        . "State: $state\n"
        . "Country: $country\n"
        . "Nationality: $nationality\n\n"
        . "We will review your registration and contact you soon.\n\nBest regards,\nTeam";
    $headersUser = "From: no-reply@yourdomain.com\r\nReply-To: no-reply@yourdomain.com";

    // ✅ Email to you (admin)
    $subjectAdmin = "New Business Registration: $businessName";
    $messageAdmin = "A new business has been registered.\n\nDetails:\n"
        . "Business Name: $businessName\n"
        . "Address: $address\n"
        . "Contact Person: $contactPerson\n"
        . "Email: $email\n"
        . "Phone: $phone\n"
        . "Business Type: $businessType\n"
        . "Website: $website\n"
        . "State: $state\n"
        . "Country: $country\n"
        . "Nationality: $nationality\n";
    $headersAdmin = "From: no-reply@yourdomain.com\r\nReply-To: $email";

    $userSent = mail($toUser, $subjectUser, $messageUser, $headersUser);
    $adminSent = mail($adminEmail, $subjectAdmin, $messageAdmin, $headersAdmin);

    if ($userSent && $adminSent) {
        echo "<p style='color:green;'>Business registered successfully! Confirmation email sent.</p>";
    } else {
        echo "<p style='color:red;'>Registration saved, but email failed to send.</p>";
    }
} else {
    echo "<p style='color:red;'>Invalid request.</p>";
}
?>