<?php

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Contracts\Validation\Validator;
    use Illuminate\Http\Exceptions\HttpResponseException;

    class StoreUserRequest extends FormRequest
    {
        public function authorize(): bool
        {
            return true;
        }

        public function rules(): array
        {
            return [
                'login' => 'required|string|min:4|unique:users',
                'password' => 'sometimes|string|min:6|regex:/[a-zA-Z]/|regex:/[0-9]/|regex:/[_\-,.]/',
                'first_name' => 'required|string|regex:/^[A-Z]/',
                'last_name' => 'required|string|regex:/^[A-Z]/',
                'email' => 'required|email|unique:users'
            ];
        }

        protected function failedValidation(Validator $validator)
        {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors()
            ], 422));
        }

    }
