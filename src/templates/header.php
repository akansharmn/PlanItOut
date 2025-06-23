<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanItOut</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Include HTMX from CDN -->

                                                                                                                              <script src="https://unpkg.com/htmx.org@2.0.4" integrity="sha384-HGfztofotfshcF7+8n44JQL2oJmowVChPTg48S+jvZoztPfvwD79OC/LTtG6dMp+" crossorigin="anonymous"></script>
    <!-- Material Icons (keeping for icon support) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<!-- HTMX script for form reset -->
<script>
  document.addEventListener('htmx:afterSettle', function(event) {
    // Check if the resetForm event was triggered
    const triggerHeader = event.detail.xhr && event.detail.xhr.getResponseHeader('HX-Trigger');
    if (triggerHeader) {
      try {
        const triggers = JSON.parse(triggerHeader);
        if (triggers.resetForm) {
          // Reset the form that triggered the request
          const sourceElement = event.detail.elt;
          if (sourceElement && sourceElement.tagName === 'FORM') {
            sourceElement.reset();
          } else {
            // Find forms in the document and check if they have htmx attributes
            const forms = document.querySelectorAll('form[hx-post]');
            forms.forEach(form => {
              form.reset();
            });
          }
        }
      } catch (e) {
        console.error('Error parsing HX-Trigger header:', e);
      }
    }
  });
</script>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .page-title {
            color: #333;
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (isset($_SESSION['user'])): ?>
        <div class="text-end mt-2">
            <a href="/logout" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
        <?php endif; ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">PlanItOut</a>

            <?php
            // Include auth manager
            require_once 'src/auth/AuthManager.php';
            $auth = \PlanItOut\Auth\AuthManager::getInstance();

            // Display appropriate nav links based on auth status
            if ($auth->isLoggedIn()):
                $user = $auth->getCurrentUser();
            ?>
            <div class="navbar-nav ms-auto">
                <span class="nav-item nav-link">Welcome, <?= htmlspecialchars($user['username']) ?></span>
                <a href="/logout" class="nav-item nav-link text-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
            <?php else: ?>
            <div class="navbar-nav ms-auto">
                <a class="nav-item nav-link" href="/login">Login</a>
            </div>
            <?php endif; ?>
        </div>
    </nav>

        <div id="main-content" class="container mt-4">
            <!-- HTMX content will be loaded here -->
        </div>
    </div>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>