<?php
// submit.php - Server-side validation and saving to database
session_start();

// Initialize error and old data variables
$errors = [];
$old = [];

// Set old data from session if available
if (isset($_SESSION['old'])) {
    $old = $_SESSION['old'];
    unset($_SESSION['old']);
}

// Database connection
require __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // If accessed directly without POST, redirect back to form
    header('Location: index.php');
    exit;
}

// Helper to sanitize input
function sanitize($value) {
    global $pdo;
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

// Validate full name
if (!isset($_POST['full_name']) || empty(trim($_POST['full_name']))) {
    $errors['full_name'] = 'Full name is required';
} else {
    $name = sanitize($_POST['full_name']);
    if (!preg_match('/^[A-Za-z\s]{3,60}$/', $name)) {
        $errors['full_name'] = 'Name must be 3-60 letters and spaces only';
    } else {
        $old['full_name'] = $name;
    }
}

// Validate email
if (!isset($_POST['email']) || empty(trim($_POST['email']))) {
    $errors['email'] = 'Email is required';
} else {
    $email = sanitize($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address';
    } else {
        $old['email'] = $email;
    }
}

// Validate phone
if (!isset($_POST['phone']) || empty(trim($_POST['phone']))) {
    $errors['phone'] = 'Phone number is required';
} else {
    $phone = sanitize($_POST['phone']);
    if (!preg_match('/^[+]?[0-9]{10,15}$/', $phone)) {
        $errors['phone'] = 'Phone must be 10-15 digits, leading + allowed';
    } else {
        $old['phone'] = $phone;
    }
}

// Validate age
if (!isset($_POST['age']) || empty(trim($_POST['age']))) {
    $errors['age'] = 'Age is required';
} else {
    $age = trim($_POST['age']);
    if (!preg_match('/^\d+$/', $age) || $age < 1 || $age > 120) {
        $errors['age'] = 'Age must be a whole number between 1 and 120';
    } else {
        $old['age'] = $age;
    }
}

// Validate gender
if (!isset($_POST['gender']) || empty(trim($_POST['gender']))) {
    $errors['gender'] = 'Gender is required';
} else {
    $gender = sanitize($_POST['gender']);
    $old['gender'] = $gender;
}

// Validate consultation type
if (!isset($_POST['consultation_type']) || empty(trim($_POST['consultation_type']))) {
    $errors['consultation_type'] = 'Consultation type is required';
} else {
    $consultation_type = sanitize($_POST['consultation_type']);
    $old['consultation_type'] = $consultation_type;
}

// Validate department
if (!isset($_POST['department']) || empty(trim($_POST['department']))) {
    $errors['department'] = 'Medical department is required';
} else {
    $department = sanitize($_POST['department']);
    $old['department'] = $department;
}

// Validate preferred date
if (!isset($_POST['preferred_date']) || empty(trim($_POST['preferred_date']))) {
    $errors['preferred_date'] = 'Preferred date is required';
} else {
    $preferred_date = sanitize($_POST['preferred_date']);
    // Check if date is today or in the future
    $today = date('Y-m-d');
    if ($preferred_date < $today) {
        $errors['preferred_date'] = 'Preferred date must be today or a future date';
    } else {
        $old['preferred_date'] = $preferred_date;
    }
}

// Validate symptoms
if (!isset($_POST['symptoms']) || empty(trim($_POST['symptoms']))) {
    $errors['symptoms'] = 'Symptoms description is required';
} else {
    $symptoms = sanitize($_POST['symptoms']);
    if (strlen($symptoms) < 10) {
        $errors['symptoms'] = 'Symptoms must be at least 10 characters';
    } elseif (strlen($symptoms) > 500) {
        $errors['symptoms'] = 'Symptoms must not exceed 500 characters';
    } else {
        $old['symptoms'] = $symptoms;
    }
}

// Validate consent checkbox
// A checked checkbox submits the string "on", so we check for that value.
if (!isset($_POST['consent']) || $_POST['consent'] !== 'on') {
    $errors['consent'] = 'You must consent to submit the form';
} else {
    $old['consent'] = true;
}

// If there are errors, go back to form with old data
if (!empty($errors)) {
    // Store old data in session for repopulation on index.php
    $_SESSION['old'] = $old;
    // Store errors in session for display on index.php
    $_SESSION['errors'] = $errors;
    // Store flash message
    $_SESSION['flash_message'] = [
        'type' => 'danger',
        'text' => 'Please fix the errors below and resubmit.'
    ];
    header('Location: index.php');
    exit;
}

// If we get here, all validation passed - save to database
try {
    $stmt = $pdo->prepare(
        "INSERT INTO consultations (full_name, email, phone, age, gender, consultation_type, department, preferred_date, symptoms, status) 
         VALUES (:full_name, :email, :phone, :age, :gender, :consultation_type, :department, :preferred_date, :symptoms, 'Pending')"
    );

    $stmt->execute([
        ':full_name' => $old['full_name'],
        ':email' => $old['email'],
        ':phone' => $old['phone'],
        ':age' => $old['age'],
        ':gender' => $old['gender'],
        ':consultation_type' => $old['consultation_type'],
        ':department' => $old['department'],
        ':preferred_date' => $old['preferred_date'],
        ':symptoms' => $old['symptoms'],
    ]);

    // Redirect to success page with the new ID
    $newId = $pdo->lastInsertId();
    header("Location: success.php?id=$newId");
    exit;
} catch (\PDOException $e) {
    // Friendly error message, never raw database error
    $_SESSION['flash_message'] = [
        'type' => 'danger',
        'text' => 'Database error. Please try again later. If the problem persists, contact support.'
    ];
    header('Location: index.php');
    exit;
}