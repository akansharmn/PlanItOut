<?php
namespace PlanItOut\Api;

include 'src/templates/header.php';

// Include the home.htmx content
$homeHtmxContent = file_get_contents('src/api/home.htmx');

//Here's where the change is applied.  The below code replaces the old quick actions section.  The rest of home.htmx is assumed unchanged.  This is a critical assumption and may break if the structure of home.htmx is different.  Robust solution would require parsing home.htmx and manipulating its DOM.
$homeHtmxContent = str_replace('<div class="row mb-4">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <a href="/createRecipePage" class="btn btn-outline-primary btn-lg w-100">
                            <i class="bi bi-plus-circle me-2"></i>Create Recipe
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="/createMealPreferencePage" class="btn btn-outline-success btn-lg w-100">
                            <i class="bi bi-heart me-2"></i>Create Meal Preference
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>', '<div class="row mb-4">
    <div class="col">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <a href="/createRecipePage" class="btn btn-outline-primary btn-lg w-100">
                            <i class="bi bi-plus-circle me-2"></i>Create Recipe
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>', $homeHtmxContent);


echo $homeHtmxContent;
?>

<?php  include 'src/templates/footer.php'; ?>