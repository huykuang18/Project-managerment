<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectPutRequest extends FormRequest
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
            'project_name' => 'required',
            'customer' => 'required',
            'description' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'project_name' => 'Tên dự án',
            'customer' => 'Khách hàng',
            'description' => 'Mô tả dự án',
            'date_start' => 'Ngày bắt đầu',
            'date_end' => 'Ngày kết thúc',
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
            'required' => ':attribute không được để trống'
        ];
    }
}
