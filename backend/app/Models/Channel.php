<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    // 実際のテーブルが、クラス名の複数形＋スネークケースであれば、書かなくてもOK
    protected $table = 'channels';

    // Eloquentを通して更新や登録が可能なフィールド（ホワイトリストを定義）
    protected $fillable = ['uuid', 'name'];
}
