<?php

namespace PlanItOut;

public class Recipe {
    // Define properties here
    private string $recipeName;
    private string $ingredients;
    private string $prerequisites;

    // Constructor
    public function __construct(string $recipeName, string $ingredients, string $prerequisites) {
        $this->recipeName = recipeName;
        $this->ingredients = $ingredients;
        $this->$prerequisites = $prerequisites;
    }

    // Getter and Setter for recipeName
    public function getRecipeName(): string {
        return $this->recipeName;
    }

    public function setRecipeName(string $recipeName): void {
        $this->recipeName = $recipeName;
    }

    // Getter and Setter for ingredients
    public function getIngredients() : string{
        return $this->ingredients;
    }

    public function setIngredients(string $ingredients): void {
        $this->ingredients = $ingredients;
    }

    // Getter and Setter for prerequisites
    public function getPrerequisites(): string {
        return $this->prerequisites;
    }

    public function setPrerequisites(string $prerequisites): void {
        $this->prerequisites = $prerequisites;
    }
}