<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // ユーザーがこのリクエストを行うことを許可するかどうかを設定します
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nickname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'profile_photo' => 'nullable|image|max:4096',
            'prefecture' => 'required|string',
            'city' => 'nullable|string|max:255',
            'children_birthdates.*' => 'nullable|date_format:Y-m-d',
            'introduction' => 'nullable|string|max:1000',
        ];
    }
}
