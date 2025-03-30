<?php

    namespace App\Http\Requests;

    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Http\Exceptions\HttpResponseException;

    class UpdateUserRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return true;
        }

        public function rules(): array
        {
            return [
                'login' => 'sometimes|string|min:4|unique:users,login',
                'password' => 'sometimes|string|min:6|regex:/[a-zA-Z]/|regex:/[0-9]/|regex:/[_\-,.]/',
                'first_name' => 'sometimes|string|regex:/^[A-Z]/',
                'last_name' => 'sometimes|string|regex:/^[A-Z]/',
                'email' => 'sometimes|email|unique:users,email'
            ];
        }

        protected function failedValidation(Validator $validator)
        {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors()
            ], 422));
        }
    }
