<?php
namespace PlanItOut\Api;

include 'src/templates/header.php';

// Include the createRecipePage.htmx content
$loginPage = file_get_contents('src/api/login.htmx');
echo $loginPage;

include 'src/templates/footer.php';
?>