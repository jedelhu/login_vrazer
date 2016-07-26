<?php

    namespace App\Http\Requests;

    use App\Http\Requests\Request;

    class CreateConnectionRequest extends Request {

        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize() {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules() {


            return [
                'host' => 'required',
                'username' => 'required',
                'password' => 'required|min:8',
                'port' => 'required',
            ];
        }

    }
    