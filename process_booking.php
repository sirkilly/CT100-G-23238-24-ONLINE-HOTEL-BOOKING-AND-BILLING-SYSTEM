<?php
// Sunrise Hotel booking processor. This server recalculates all charges independently.
function clean($value) {
    return htmlspecialchars(trim((string)$value), ENT_QUOTES, 'UTF-8');
}

function is_checked($key) {
    return isset($_POST[$key]) && $_POST[$key] === 'yes';
}

$errors = [];
$fullName = trim($_POST['full_name'] ?? '');
$identity = trim($_POST['identity'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$checkIn = trim($_POST['check_in'] ?? '');
$nights = filter_var($_POST['nights'] ?? null, FILTER_VALIDATE_INT);
$roomType = trim($_POST['room_type'] ?? '');
$guests = filter_var($_POST['guests'] ?? null, FILTER_VALIDATE_INT);

$roomRates = ['single' => 3500, 'double' => 5000, 'family' => 7500];
$roomCapacities = ['single' => 1, 'double' => 2, 'family' => 4];
$roomNames = ['single' => 'Single Room', 'double' => 'Double Room', 'family' => 'Family Room'];

if ($fullName === '') $errors[] = 'Full name is required.';
if ($identity === '') $errors[] = 'National ID or passport number is required.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email address is required.';
if (!preg_match('/^[0-9]{10}$/', $phone)) $errors[] = 'Phone number must contain exactly 10 digits.';
if ($checkIn === '') {
    $errors[] = 'Check-in date is required.';
} else {
    $date = DateTime::createFromFormat('Y-m-d', $checkIn);
    $dateErrors = DateTime::getLastErrors();
    if (!$date || ($dateErrors !== false && ($dateErrors['warning_count'] > 0 || $dateErrors['error_count'] > 0)) ||
        $date->format('Y-m-d') !== $checkIn || $checkIn < date('Y-m-d')) {
        $errors[] = 'Please provide a valid check-in date that is not in the past.';
    }
}
if ($nights === false || $nights < 1 || $nights > 14) $errors[] = 'Number of nights must be from 1 to 14.';
if (!isset($roomRates[$roomType])) $errors[] = 'Please select a valid room type.';
if ($guests === false || $guests < 1) $errors[] = 'Number of guests must be greater than zero.';
if (isset($roomCapacities[$roomType]) && $guests !== false && $guests > $roomCapacities[$roomType]) {
    $errors[] = 'The selected room cannot hold that number of guests.';
}

$breakfast = is_checked('breakfast');
$airportTransfer = is_checked('airport_transfer');
$conferenceRoom = is_checked('conference_room');
$transferPreference = trim($_POST['transfer_preference'] ?? 'arrival');
if (!in_array($transferPreference, ['arrival', 'departure', 'both'], true)) $transferPreference = 'arrival';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    $errors[] = 'Please submit the booking form to continue.';
}

if ($errors) {
    http_response_code(400);
}
$canCalculate = isset($roomRates[$roomType]) && $nights !== false && $nights >= 1 && $nights <= 14 &&
    $guests !== false && $guests > 0;
$total = 0;
$roomCost = 0;
$breakfastCost = 0;
$transferCost = 0;
$conferenceCost = 0;

if ($canCalculate) {
    $roomCost = $roomRates[$roomType] * $nights;
    $breakfastCost = $breakfast ? 700 * $guests * $nights : 0;
    $transferCost = $airportTransfer ? 2000 : 0;
    // Admission number 23238 ends in 8: conference room hire costs KSh 5,000.
    $conferenceCost = $conferenceRoom ? 5000 : 0;
    $total = $roomCost + $breakfastCost + $transferCost + $conferenceCost;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation | Sunrise Hotel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.html"><span class="brand-mark">S</span> SUNRISE <span class="brand-light">HOTEL</span></a>
    <nav><a href="index.html">Home</a><a href="booking.html">Book a Room</a></nav>
</header>
<main class="booking-page">
    <?php if ($errors): ?>
        <section class="confirmation error-panel">
            <p class="eyebrow">BOOKING NOT COMPLETED</p>
            <h1>Please check your details</h1>
            <p>We could not accept this booking yet:</p>
            <ul><?php foreach ($errors as $error): ?><li><?php echo clean($error); ?></li><?php endforeach; ?></ul>
            <a class="button button-burgundy" href="booking.html">Return to booking form</a>
        </section>
    <?php else: ?>
        <section class="confirmation">
            <div class="confirmation-icon">✓</div>
            <p class="eyebrow">SUNRISE HOTEL</p>
            <h1>Booking Confirmed</h1>
            <p class="confirmation-intro">Thank you for choosing Sunrise Hotel. Please review your booking details below.</p>
            <div class="confirmation-details">
                <div><span>Customer</span><strong><?php echo clean($fullName); ?></strong></div>
                <div><span>National ID / Passport</span><strong><?php echo clean($identity); ?></strong></div>
                <div><span>Email</span><strong><?php echo clean($email); ?></strong></div>
                <div><span>Phone</span><strong><?php echo clean($phone); ?></strong></div>
                <div><span>Check-in date</span><strong><?php echo clean($checkIn); ?></strong></div>
                <div><span>Room</span><strong><?php echo clean($roomNames[$roomType]); ?></strong></div>
                <div><span>Guests</span><strong><?php echo (int)$guests; ?></strong></div>
                <div><span>Number of nights</span><strong><?php echo (int)$nights; ?></strong></div>
                <div><span>Breakfast</span><strong><?php echo $breakfast ? 'Yes' : 'No'; ?></strong></div>
                <div><span>Airport transfer</span><strong><?php echo $airportTransfer ? 'Yes (' . clean($transferPreference) . ')' : 'No'; ?></strong></div>
                <div><span>Conference room</span><strong><?php echo $conferenceRoom ? 'Yes' : 'No'; ?></strong></div>
            </div>
            <div class="confirmation-costs">
                <div><span>Room cost</span><strong>KSh <?php echo number_format($roomCost); ?></strong></div>
                <div><span>Breakfast cost</span><strong>KSh <?php echo number_format($breakfastCost); ?></strong></div>
                <div><span>Airport transfer</span><strong>KSh <?php echo number_format($transferCost); ?></strong></div>
                <div><span>Conference room</span><strong>KSh <?php echo number_format($conferenceCost); ?></strong></div>
            </div>
            <div class="confirmation-total"><span>Total Amount</span><strong>KSh <?php echo number_format($total); ?></strong></div>
            <p class="thank-you">Thank you for choosing Sunrise Hotel.</p>
            <a class="button button-burgundy" href="booking.html">Make another booking</a>
        </section>
    <?php endif; ?>
</main>
<footer><span>© 2026 Sunrise Hotel</span><span>Comfort • Care • Hospitality</span></footer>
</body>
</html>
