<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Successful</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Thank You!</h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2>Your consultation request has been submitted</h2>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Reference Number:</strong> <?php echo htmlspecialchars($_GET['id'] ?? 'N/A'); ?>
                        </div>
                        
                        <p class="lead">A doctor will review your request and get back to you soon. You can reference your request using the number above.</p>
                        
                        <div class="text-center">
                            <a href="index.php" class="btn btn-primary px-4 py-2">Submit Another Request</a>
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