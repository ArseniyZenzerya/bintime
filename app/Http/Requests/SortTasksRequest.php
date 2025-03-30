<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;

    class SortTasksRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return true;
        }

        public function rules(): array
        {
            return [
                'sort_by' => 'nullable|in:first_name,last_name,email',
                'sort_order' => 'nullable|in:asc,desc',
            ];
        }

        public function messages(): array
        {
            return [
                'sort_by.in' => 'The sort field must be either first name, last name, or email.',
                'sort_order.in' => 'The sort order must be either asc or desc.',
            ];
        }

        protected function failedValidation(Validator $validator)
        {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors()
            ], 422));
        }
    }
