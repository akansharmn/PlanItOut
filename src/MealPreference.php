<?php
namespace PlanItOut;

class MealPreference
{
    private MealType $mealType;
     private Recipe $recipe;

    public function __construct(MealType $mealType, Recipe $recipe) {
        $this->mealType = $mealType;
        $this->recipe = $recipe;
    }
   

    // Additional methods and logic can be added here
    
}