<?php

namespace PlanItOut;

public class Recipe {
    // Define properties here
    private string $recipeName;
    private string $ingredients;
    private string $prePreparations;

    // Constructor
    public function __construct(string $recipeName, string $ingredients, string $prePreparations) {
        $this->recipeName = recipeName;
        $this->ingredients = $ingredients;
        $this->$prePreparations = $prePreparations;
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
    public function getPrePreparations(): string {
        return $this->$prePreparations;
    }

    public function setPrePreparations(string $prePreparations): void {
        $this->$prePreparations = $prePreparations;
    }
}