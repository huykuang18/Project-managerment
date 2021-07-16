<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'unique:users|required',
            'name' => 'required',
            'password' => 'required|min:6',
            'repassword' => 'required|same:password',
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'Tên đăng nhập',
            'name' => 'Họ tên',
            'password' => 'Mật khẩu',
            'repassword' => 'Xác nhận mật khẩu'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => ':attribute không được để trống',
            'unique' => ':attribute đã tồn tại',
            'password.min' => 'mật khẩu không được ít hơn 6 ký tự',
            'repassword.same' => 'xác nhận mật khẩu không khớp'
        ];
    }
}
