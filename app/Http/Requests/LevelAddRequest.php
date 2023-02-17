<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LevelAddRequest extends FormRequest
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
            'name'=>'required|unique:levels',
            'decription'=>'required',
            'require_number_candidate'=>'required|numeric',
            'require_number_pair'=>'required|numeric',
            'require_number_date_accepted'=>'required|numeric',
            'require_number_date_action'=>'required|numeric',
            'require_number_lover'=>'required|numeric',
            'commission_candidate'=>'required|numeric',
            'commission_pair'=>'required|numeric',
            'commission_date_accepted'=>'required|numeric',
            'commission_date_action'=>'required|numeric',
            'commission_lover'=>'required|numeric',
        ];
    }

//    public function messages()
//    {
//        return [
//            'name.required' => 'Tên không được bỏ trống',
//            'name.unique' => 'Tên đã tồn tại',
//            'name.max' => 'Tên tối đã 255',
//            'name.min' => 'Tên tối thiểu 10',
//            'decription.required' => 'Mô tả không được bỏ trống',
//            'image_path.required' => 'Ảnh không được bỏ trống',
//        ];
//    }
}
