<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpotRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // 認証が必要な場合は適宜変更
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'prefecture' => 'required|string',
            'city' => 'required|string|max:100',
            'description' => 'required|string',
            'date_visited' => 'required|date',
            'child_age_range' => 'required|string|max:50',
            'rating' => 'required|integer|min:1|max:5',
            'photos.*' => 'nullable|image|max:4096',
            'spot_url' => 'nullable|url|max:255',
        ];
    }
}
