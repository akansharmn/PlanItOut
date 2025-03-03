namespace PlanItOut;

class WeeklyPlan {
    private int $year;
    private int $week;
    private DateTime $weekStartDate;
    private DateTime $weekEndDate;
    private array[DayMealPlan] mealPlans; 
     

    public function getYear(): int {
        return $this->year;
    }

    public function setYear(int $year): void {
        $this->year = $year;
    }

    public function getWeek(): int {
        return $this->week;
    }

    public function setWeek(int $week): void {
        $this->week = $week;
    }

    public function getWeekStartDate(): DateTime {
        return $this->weekStartDate;
    }

    public function setWeekStartDate(DateTime $weekStartDate): void {
        $this->weekStartDate = $weekStartDate;
    }

    public function getWeekEndDate(): DateTime {
        return $this->weekEndDate;
    }

    public function setWeekEndDate(DateTime $weekEndDate): void {
        $this->weekEndDate = $weekEndDate;
    }

    public function getMealPlans(): array[DayMealPlan]{
        return $this->mealPlans;
    }

    public function setMealPlans(array[DayMealPlan] $mealPlans): void {
         $this->mealPlans.push($mealPlans);
    }

    public function addMealPlan(DayMealPlan $mealPlan): void{
        $this->mealPlans.push($mealPlan);
    }

   public function addMealPlan(Weekday weekday, MealType mealType, string recipeName): void){
        DayMealPlan = new DayMealPlan(this->year,this->week, weekday, mealType, recipeName);
        $this->mealPlans.push($DayMealPlan);
    }

    public function removeMealPlan(Weekday weekday, MealTpe mealType, string recipeName): void){
        // to do
    }

   
}