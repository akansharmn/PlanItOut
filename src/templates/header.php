
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanItOut</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Include HTMX from CDN -->
    <script src="https://unpkg.com/htmx.org@1.9.10" integrity="sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLfu/aRVyM" crossorigin="anonymous"></script>
    <!-- Material Icons (keeping for icon support) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

<!-- HTMX script for form reset -->
<script>
  document.addEventListener('htmx:afterSettle', function(event) {
    // Check if the resetForm event was triggered
    const triggerHeader = event.detail.xhr && event.detail.xhr.getResponseHeader('HX-Trigger');
    if (triggerHeader) {
      const triggers = JSON.parse(triggerHeader);
      if (triggers.resetForm) {
        // Find the form and reset it
        const form = document.querySelector('form[hx-post="/createRecipe"]');
        if (form) form.reset();
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
