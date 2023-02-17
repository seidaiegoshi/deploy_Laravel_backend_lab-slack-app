<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MyIconStoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        $maxKB = 1024 * 5; // 5MB
        return [
            'image' => "required|image|max:{$maxKB}", // 直接5120と書いても良いが単位が分かりづらいので説明変数を利用しているだけです
        ];
    }
}
