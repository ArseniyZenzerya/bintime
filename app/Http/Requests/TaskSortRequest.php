<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;

    class TaskSortRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return true;
        }

        public function rules(): array
        {
            return [
                'sort_by' => 'nullable|in:title,status',
                'sort_order' => 'nullable|in:asc,desc',
            ];
        }
    }
