<?php
session_start();

// Check if admin is logged in, if not show login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Handle login form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require __DIR__ . '/config/admin.php';

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($username === $adminUsername && $password === $adminPassword) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: admin.php');
            exit;
        } else {
            $loginError = 'Invalid username or password';
        }
    }
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Admin Login</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($loginError)): ?>
                            <div class="alert alert-danger mb-3"><?= htmlspecialchars($loginError) ?></div>
                        <?php endif; ?>
                        
                        <form action="admin.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap 5 CDN JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    exit;
}
?>

<!-- Admin dashboard - shows consultation requests -->
<?php
// Include database config
require __DIR__ . '/config/db.php';

// Handle status toggle (Pending <-> Reviewed)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_status'], $_POST['request_id'])) {
    $requestId = (int) $_POST['request_id'];
    $currentStatus = ($_POST['current_status'] === 'Reviewed') ? 'Reviewed' : 'Pending';
    $newStatus = ($currentStatus === 'Reviewed') ? 'Pending' : 'Reviewed';

    try {
        // Prepared statement to update only the status of one request
        $update = $pdo->prepare("UPDATE consultations SET status = :status WHERE id = :id");
        $update->execute([':status' => $newStatus, ':id' => $requestId]);
    } catch (\PDOException $e) {
        $errorMessage = 'Could not update the status. Please try again.';
    }

    // Redirect to avoid resubmitting the form on refresh
    header('Location: admin.php');
    exit;
}

// Fetch all requests newest first
try {
    $stmt = $pdo->query("SELECT id, full_name, email, phone, department, consultation_type, preferred_date, status, created_at FROM consultations ORDER BY created_at DESC");
    $requests = $stmt->fetchAll();
} catch (\PDOException $e) {
    $errorMessage = 'Database error: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Consultation Requests</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-9 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Consultation Requests</h4>
                    </div>
                    <div class="card-body p-4">
                        <?php if (isset($errorMessage)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                        <?php elseif (empty($requests)): ?>
                            <div class="alert alert-info">
                                No consultation requests yet.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Department</th>
                                            <th>Type</th>
                                            <th>Preferred Date</th>
                                            <th>Status</th>
                                            <th>Submitted</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($requests as $idx => $req): ?>
                                            <tr>
                                                <td><?= $idx + 1 ?></td>
                                                <td><?= htmlspecialchars($req['full_name']) ?></td>
                                                <td><?= htmlspecialchars($req['email']) ?></td>
                                                <td><?= htmlspecialchars($req['phone']) ?></td>
                                                <td><?= htmlspecialchars($req['department']) ?></td>
                                                <td>
                                                    <?php 
                                                    $typeMap = [
                                                        'Video call' => '📹 Video',
                                                        'Phone call' => '📞 Phone', 
                                                        'Chat' => '💬 Chat'
                                                    ];
                                                    echo $typeMap[$req['consultation_type']] ?? $req['consultation_type'];
                                                    ?>
                                                </td>
                                                <td><?= htmlspecialchars($req['preferred_date']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $req['status'] === 'Reviewed' ? 'secondary' : 'primary' ?>">
                                                        <?= htmlspecialchars($req['status']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($req['created_at']) ?></td>
                                                <td>
                                                    <!-- Toggle Pending/Reviewed status -->
                                                    <form action="admin.php" method="POST" class="d-inline">
                                                        <input type="hidden" name="request_id" value="<?= (int) $req['id'] ?>">
                                                        <input type="hidden" name="current_status" value="<?= htmlspecialchars($req['status']) ?>">
                                                        <button type="submit" name="toggle_status" value="1" class="btn btn-sm btn-outline-secondary">
                                                            <?= $req['status'] === 'Reviewed' ? 'Mark Pending' : 'Mark Reviewed' ?>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                        
                        <div class="text-end mt-3">
                            <a href="logout.php" class="btn btn-link text-danger">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <!-- Bootstrap 5 CDN JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>