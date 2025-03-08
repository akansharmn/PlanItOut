<?php
namespace PlanItOut\Api;

//include 'src/templates/header.php';

// Include the createRecipePage.htmx content
$createRecipeContent = file_get_contents('src/api/myCreateRecipePage.htmx');
echo $createRecipeContent;
?>

