class DayMealPlan {
    public Int $year;
    public Int $week;
    public Weekday $weekday;
    public MealType $mealType;
    public string $recipeName;

    
    public function __construct(Int $year, Int $week, Weekday $weekday, MealType $mealType, Recipe $recipe) {
        $this->year = $year;
        $this->week = $week;
        $this->weekday = $weekday;
        $this->mealType = $mealType;
        $this->recipe = $recipe;
    }
}