<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // 実際のテーブルが、クラス名の複数形＋スネークケースであれば、書かなくてもOK
    protected $table = 'messages';

    // Eloquentを通して更新や登録が可能なフィールド（ホワイトリストを定義）
    protected $fillable = ['channel_id', 'user_id', 'content'];

    protected $dateFormat = 'Y-m-d H:i:s.v';
}
