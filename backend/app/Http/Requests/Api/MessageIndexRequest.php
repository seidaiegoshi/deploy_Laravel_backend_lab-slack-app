<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Channel;

class MessageIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // $this->route('uuid') でパスパラメータを取得しつつ、channelsテーブルからチャンネルデータを取得
        $channel = Channel::where('uuid', $this->route('uuid'))->first();
        // 先ほど作成したポリシークラスのshowメソッドに渡してチェック
        //apiで要求してきたユーザーが、チャンネルのshowができるか？
        return $this->user()->can('show', $channel);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
