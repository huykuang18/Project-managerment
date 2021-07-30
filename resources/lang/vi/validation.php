<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'accepted' => ':attribute phải được chấp nhận.',
    'active_url' => ':attribute không phải là một URL hợp lệ.',
    'after' => ':attribute phải là một ngày sau :date.',
    'after_or_equal' => ':attribute phải là một ngày sau hoặc trùng ngày :date.',
    'alpha' => ':attribute chỉ gồm các chữ cái.',
    'alpha_dash' => ':attribute chỉ bao gồm các chữ cái, số, dấu gạch ngang và dấu gạch dưới.',
    'alpha_num' => ':attribute chỉ bao gồm các chữ cái và số.',
    'array' => ':attribute phải là một mảng.',
    'before' => ':attribute phải là một ngày trước :date.',
    'before_or_equal' => ':attribute phải là một ngày trước hoặc trùng ngày :date.',
    'between' => [
        'numeric' => ':attribute phải nằm giữa :min và :max.',
        'file' => ':attribute phải nằm giữa :min và :max kilobytes.',
        'string' => ':attribute phải nằm giữa :min và :max ký tự.',
        'array' => ':attribute phải có giữa :min và :max phần.',
    ],
    'boolean' => ':attribute phải trả về dạng true hoặc false.',
    'confirmed' => ':attribute xác nhận không phù hợp.',
    'current_password' => 'Mật khẩu không đúng.',
    'date' => ':attribute không hợp lệ.',
    'date_equals' => ':attribute phải trùng với ngày :date.',
    'date_format' => ':attribute không khớp với định dạng :format.',
    'different' => ':attribute và :other phải khác nhau.',
    'digits' => ':attribute cần phải có :digits chữ số.',
    'digits_between' => ':attribute phải có từ :min đến :max chữ số.',
    'dimensions' => ':attribute có kích thước hình ảnh không hợp lệ.',
    'distinct' => ':attribute bị trùng lặp.',
    'email' => ':attribute cần phải có địa chỉ email hợp lệ.',
    'ends_with' => ':attribute phải kết thúc bởi: :values.',
    'exists' => 'Phần được chọn :attribute không có hiệu lực.',
    'file' => ':attribute cần phải là một file.',
    'filled' => ':attribute cần phải có giá trị.',
    'gt' => [
        'numeric' => ':attribute cần phải lớn hơn :value.',
        'file' => ':attribute cần phải lớn hơn :value kilobytes.',
        'string' => ':attribute cần phải lớn hơn :value ký tự.',
        'array' => ':attribute cần có nhiều hơn :value phần.',
    ],
    'gte' => [
        'numeric' => ':attribute cần phải lớn hơn hoặc bằng :value.',
        'file' => ':attribute cần phải lớn hơn hoặc bằng :value kilobytes.',
        'string' => ':attribute cần phải lớn hơn hoặc bằng :value ký tự.',
        'array' => ':attribute cần có :value phần hoặc nhiều hơn.',
    ],
    'image' => ':attribute cần phải là một hình ảnh.',
    'in' => 'Phần được chọn :attribute không có hiệu lực.',
    'in_array' => ':attribute không tồn tại trong :other.',
    'integer' => ':attribute cần phải là một số nguyên.',
    'ip' => ':attribute cần phải là địa chỉ IP hợp lệ.',
    'ipv4' => ':attribute cần phải là địa chỉ IPv4 hợp lệ.',
    'ipv6' => ':attribute cần phải là địa chỉ IPv6 hợp lệ.',
    'json' => ':attribute cần phải là chuỗi JSON hợp lệ.',
    'lt' => [
        'numeric' => ':attribute cần phải ít hơn :value.',
        'file' => ':attribute cần phải ít hơn :value kilobytes.',
        'string' => ':attribute cần phải ít hơn :value ký tự.',
        'array' => ':attribute must have ít hơn :value phần.',
    ],
    'lte' => [
        'numeric' => ':attribute cần phải ít hơn hoặc bằng :value.',
        'file' => ':attribute cần phải ít hơn hoặc bằng :value kilobytes.',
        'string' => ':attribute cần phải ít hơn hoặc bằng :value ký tự.',
        'array' => ':attribute không được nhiều hơn :value phần.',
    ],
    'max' => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'file' => ':attribute không được lớn hơn :max kilobytes.',
        'string' => ':attribute không được lớn hơn :max ký tự.',
        'array' => ':attribute không được có nhiều hơn :max phần.',
    ],
    'mimes' => ':attribute cần phải là một tệp loại: :values.',
    'mimetypes' => ':attribute cần phải là một tệp loại: :values.',
    'min' => [
        'numeric' => ':attribute cần phải có ít nhất :min.',
        'file' => ':attribute cần phải có ít nhất :min kilobytes.',
        'string' => ':attribute cần phải có ít nhất :min ký tự.',
        'array' => ':attribute must have có ít nhất :min phần.',
    ],
    'multiple_of' => ':attribute cần phải là bội số của :value.',
    'not_in' => 'Phần được chọn :attribute không có hiệu lực.',
    'not_regex' => 'Định dạng :attribute không có hiệu lực.',
    'numeric' => ':attribute cần phải là một số.',
    'password' => 'Mật khẩu không đúng.',
    'present' => ':attribute cần phải có.',
    'regex' => 'Định dạng :attribute không có hiệu lực.',
    'required' => ':attribute không được để trống.',
    'required_if' => ':attribute không được để trống khi :other là :value.',
    'required_unless' => ':attribute không được để trống trừ khi :other nằm trong :values.',
    'required_with' => ':attribute không được để trống khi :values hiện có.',
    'required_with_all' => ':attribute không được để trống khi :values hiện có.',
    'required_without' => ':attribute không được để trống khi :values hiện không có.',
    'required_without_all' => ':attribute không được để trống khi không có :values hiện tại.',
    'prohibited' => ':attribute bị cấm.',
    'prohibited_if' => ':attribute bị cấm khi :other là :value.',
    'prohibited_unless' => ':attribute bị cấm trừ khi :other nằm trong :values.',
    'same' => ':attribute và :other phải khớp.',
    'size' => [
        'numeric' => ':attribute cần phải :size.',
        'file' => ':attribute cần phải :size kilobytes.',
        'string' => ':attribute cần phải :size ký tự.',
        'array' => ':attribute phải chứa :size phần.',
    ],
    'starts_with' => ':attribute phải bắt đầu với một trong những điều sau đây: :values.',
    'string' => ':attribute cần phải là một chuỗi.',
    'timezone' => ':attribute cần phải là một múi giờ hợp lệ.',
    'unique' => ':attribute đã được thực hiện.',
    'uploaded' => ':attribute không thể tải lên.',
    'url' => ':attribute cần phải là một URL hợp lệ.',
    'uuid' => ':attribute cần phải là một UUID hợp lệ.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        /**User */
        'username' => 'Tên đăng nhập',
        'name' => 'Tên',
        'password' => 'Mật khẩu',
        'repassword' => 'Xác nhận mật khẩu',

        /**Project */
        'project_name' => 'Tên dự án',
        'description' => 'Mô tả dự án',
        'customer' => 'Khách hàng',
        'date_start' => 'Ngày bắt đầu',
        'date_end' => 'Ngày kết thúc',

        /**Item */
        'order' => 'Độ ưu tiên',
        'parent_id' => 'Mục chứa',

        /**Issue */
        'issue_name' => 'Tên công việc',
        'status' => 'Trạng thái công việc',
        'user_id' => 'Người đảm nhận',
        'item_id' => 'Mục',
        'deadline' => 'Thời hạn',
        'priority' => 'Mức ưu tiên'
    ],

];
