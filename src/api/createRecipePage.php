<?php
namespace PlanItOut\Api;

include 'src/templates/header.php';

// Include the home.htmx content
$homeHtmxContent = file_get_contents('src/api/createRecipePage.htmx');
echo $homeHtmxContent;
?>

<?php include 'src/templates/footer.php'; ?>