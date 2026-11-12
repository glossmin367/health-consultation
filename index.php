<?php
// Start session for possible error persistence from submit.php
session_start();

// Helper function to repopulate form fields with old data
function old($name) {
    // Check POST first (current submission),
    // then session data (after redirect from submit.php)
    return $_POST[$name] ?? (isset($_SESSION['old']) ? $_SESSION['old'][$name] : '');
}

// Extract errors from session (set by submit.php)
if (isset($_SESSION['errors']) && is_array($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
} else {
    $errors = [];
}

// Store old values from current POST if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['old'] = $_POST;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Health Consultation Form</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Health Consultation Request</h4>
                    </div>
                    <div class="card-body p-4">
                        <!-- Flash message from submit.php -->
                        <?php if (isset($_SESSION['flash_message']) && is_array($_SESSION['flash_message'])): ?>
                            <div class="alert alert-<?= $_SESSION['flash_message']['type'] ?> mb-4">
                                <?= htmlspecialchars($_SESSION['flash_message']['text']) ?>
                            </div>
                            <?php unset($_SESSION['flash_message']); ?>
                        <?php endif; ?>

                        <form action="submit.php" method="POST" id="consultationForm">
                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control <?= isset($errors['full_name']) ? 'is-invalid' : '' ?>" 
                                       id="full_name" name="full_name" 
                                       placeholder="Enter your full name" 
                                       minlength="3" maxlength="60"
                                       value="<?= htmlspecialchars(old('full_name')) ?>"
                                       required>
                                <?php if (isset($errors['full_name'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['full_name']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                                       id="email" name="email" 
                                       placeholder="Enter your email" 
                                       value="<?= htmlspecialchars(old('email')) ?>"
                                       required>
                                <?php if (isset($errors['email'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['email']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Phone Number -->
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control <?= isset($errors['phone']) ? 'is-invalid' : '' ?>" 
                                       id="phone" name="phone" 
                                       placeholder="Enter phone number (10-15 digits, leading + allowed)" 
                                       pattern="[\+]?[0-9]{10,15}"
                                       title="10 to 15 digits, leading + allowed"
                                       value="<?= htmlspecialchars(old('phone')) ?>"
                                       required>
                                <div class="form-text">Example: +1234567890 or 1234567890</div>
                                <?php if (isset($errors['phone'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['phone']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Age -->
                            <div class="mb-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="form-control <?= isset($errors['age']) ? 'is-invalid' : '' ?>" 
                                       id="age" name="age" 
                                       min="1" max="120"
                                       value="<?= htmlspecialchars(old('age')) ?>"
                                       required>
                                <?php if (isset($errors['age'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['age']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Gender -->
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select <?= isset($errors['gender']) ? 'is-invalid' : '' ?>" 
                                        id="gender" name="gender" required>
                                    <option value="" disabled selected>Select gender</option>
                                    <option value="Male" <?= (old('gender') ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= (old('gender') ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                    <option value="Other" <?= (old('gender') ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                </select>
                                <?php if (isset($errors['gender'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['gender']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Consultation Type -->
                            <div class="mb-3">
                                <label for="consultation_type" class="form-label">Consultation Type</label>
                                <select class="form-select <?= isset($errors['consultation_type']) ? 'is-invalid' : '' ?>" 
                                        id="consultation_type" name="consultation_type" required>
                                    <option value="" disabled selected>Select consultation type</option>
                                    <option value="Video call" <?= (old('consultation_type') ?? '') === 'Video call' ? 'selected' : '' ?>>Video call</option>
                                    <option value="Phone call" <?= (old('consultation_type') ?? '') === 'Phone call' ? 'selected' : '' ?>>Phone call</option>
                                    <option value="Chat" <?= (old('consultation_type') ?? '') === 'Chat' ? 'selected' : '' ?>>Chat</option>
                                </select>
                                <?php if (isset($errors['consultation_type'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['consultation_type']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Medical Department -->
                            <div class="mb-3">
                                <label for="department" class="form-label">Medical Department</label>
                                <select class="form-select <?= isset($errors['department']) ? 'is-invalid' : '' ?>" 
                                        id="department" name="department" required>
                                    <option value="" disabled selected>Select department</option>
                                    <option value="General Medicine" <?= (old('department') ?? '') === 'General Medicine' ? 'selected' : '' ?>>General Medicine</option>
                                    <option value="Pediatrics" <?= (old('department') ?? '') === 'Pediatrics' ? 'selected' : '' ?>>Pediatrics</option>
                                    <option value="Dermatology" <?= (old('department') ?? '') === 'Dermatology' ? 'selected' : '' ?>>Dermatology</option>
                                    <option value="Gynecology" <?= (old('department') ?? '') === 'Gynecology' ? 'selected' : '' ?>>Gynecology</option>
                                    <option value="Dentistry" <?= (old('department') ?? '') === 'Dentistry' ? 'selected' : '' ?>>Dentistry</option>
                                    <option value="Mental Health" <?= (old('department') ?? '') === 'Mental Health' ? 'selected' : '' ?>>Mental Health</option>
                                </select>
                                <?php if (isset($errors['department'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['department']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Preferred Date -->
                            <div class="mb-3">
                                <label for="preferred_date" class="form-label">Preferred Date</label>
                                <input type="date" class="form-control <?= isset($errors['preferred_date']) ? 'is-invalid' : '' ?>" 
                                       id="preferred_date" name="preferred_date"
                                       min="<?= date('Y-m-d') ?>"
                                       value="<?= htmlspecialchars(old('preferred_date')) ?>"
                                       required>
                                <?php if (isset($errors['preferred_date'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['preferred_date']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Symptoms Description -->
                            <div class="mb-3">
                                <label for="symptoms" class="form-label">Symptoms Description</label>
                                <textarea class="form-control <?= isset($errors['symptoms']) ? 'is-invalid' : '' ?>" 
                                          id="symptoms" name="symptoms" rows="4" cols="50"
                                          minlength="10" maxlength="500"
                                          required><?= htmlspecialchars(old('symptoms')) ?></textarea>
                                <div class="form-text">
                                    <span id="charCount">0</span> / 500 characters
                                </div>
                                <?php if (isset($errors['symptoms'])): ?>
                                    <div class="invalid-feedback"><?= htmlspecialchars($errors['symptoms']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Consent Checkbox -->
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input <?= isset($errors['consent']) ? 'is-invalid' : '' ?>" 
                                       id="consent" name="consent" value="on"
                                       <?= old('consent') ? 'checked' : '' ?> required>
                                <label class="form-check-label" for="consent">
                                    I confirm the information is correct and accurate
                                </label>
                                <?php if (isset($errors['consent'])): ?>
                                    <div class="invalid-feedback d-block"><?= htmlspecialchars($errors['consent']) ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Emergency Notice -->
                            <div class="alert alert-warning mb-3">
                                <small><strong>This form is not for emergencies.</strong> If it is an emergency, contact your nearest hospital immediately.</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 CDN JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS for character counter -->
    <script src="assets/js/script.js"></script>
</body>
</html>
<?php
// Old values and errors have been rendered once; clear them so a fresh visit starts empty.
unset($_SESSION['old']);
?>