<?php
namespace PlanItOut\Api;

include 'src/templates/header.php';

// Include the home.htmx content
$homeHtmxContent = file_get_contents('src/api/home.htmx');
echo $homeHtmxContent;
?>

<script>
    // Initialize Material Design Components
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all buttons
        const buttons = document.querySelectorAll('.mdc-button');
        buttons.forEach(button => {
            mdc.ripple.MDCRipple.attachTo(button);
        });

        // Initialize cards with ripple effect
        const cardPrimaryActions = document.querySelectorAll('.mdc-card__primary-action');
        cardPrimaryActions.forEach(element => {
            mdc.ripple.MDCRipple.attachTo(element);
        });

        // Initialize linear progress
        const linearProgress = document.querySelector('.mdc-linear-progress');
        if (linearProgress) {
            mdc.linearProgress.MDCLinearProgress.attachTo(linearProgress);
        }
    });
</script>

<?php include 'src/templates/footer.php'; ?>