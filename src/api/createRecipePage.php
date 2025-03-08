<?php
namespace PlanItOut\Api;

include 'src/templates/header.php';

// Include the createRecipePage.htmx content
$createRecipeContent = file_get_contents('src/api/createRecipePage.htmx');
echo $createRecipeContent;
?>

<?php include 'src/templates/footer.php'; ?>