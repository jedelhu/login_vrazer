<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateMysqlConnectionRequest extends Request {

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
    public function rules()
    {
        if ($this->request->get('type') == "mysql") {
            return [
                'host' => 'required',
                'username' => 'required',
                'password' => 'required'
            ];
        }else{
            return [
                'host' => 'required',
                'username' => 'required',
                'password' => 'required',
                'sshhost' => 'required',
                'sshusername' => 'required',
                'sshpassword' => 'required',
            ];


        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'host.required' => 'The host is required.',
            'username.required' => 'The username is required.',
            'password.required' => 'The password is required.',
//            'password.min'=> 'The password must be at least 8 characters.',
            'sshhost.required' => 'The SSH host is required.',
            'sshusername.required' => 'The SSH username is required.',
            'sshpassword.required' => 'The SSH password is required.',
//            'sshpassword.min' => 'The SSH password must be at least 8 characters.',
        ];
    }

}
