<?php

namespace App\Policies;

use App\Models\ChecklistSubmission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChecklistSubmissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ChecklistSubmission  $checklistSubmission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, ChecklistSubmission $checklistSubmission)
    {
        return $user->id === $checklistSubmission->user_id || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ChecklistSubmission  $checklistSubmission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ChecklistSubmission $checklistSubmission)
    {
        return $user->id === $checklistSubmission->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ChecklistSubmission  $checklistSubmission
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ChecklistSubmission $checklistSubmission)
    {
        return $user->id === $checklistSubmission->user_id;
    }
}
