<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\TaskAssign;

class WorkplaceAndImplementationDateChecker implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        private ?int $task_assign_id,
        private int $workplace_id,
        private string $implementation_date)
    {
        $this->workplace_id = $workplace_id;
        $this->implementation_date = $implementation_date;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $query = TaskAssign::where('workplace_id', $this->workplace_id)
            ->where('implementation_date', $this->implementation_date);

        if (!is_null($this->task_assign_id)) {
            $query->whereNotIn('id', [$this->task_assign_id]);
        }

        $is_exist = $query->exists();

        return !$is_exist;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '指定の作業場所の' . $this->implementation_date . 'には他の担当者が割り当て済みです';
    }
}
