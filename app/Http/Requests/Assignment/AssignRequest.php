<?php

namespace App\Http\Requests\Assignment;

use App\Models\Assignment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AssignRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'task_id'     => [
                'required',
                Rule::exists(Task::class, 'id')->where(function ($query) {
                        return $query->where('assignor_id', Auth::id())
                            ->orWhere('id', Assignment::query()
                                ->select('task_id')
                                ->where('assignee_id', Auth::id())
                                ->value('task_id')
                            );
                    }),
            ],
            'assignee_email' => [
                'required',
                'email',
                Rule::exists(User::class, 'email')->whereNot('id', Auth::id())
            ]
        ];
    }
}
