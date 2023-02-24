<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Channel extends Model
{
    use HasFactory;

    // 実際のテーブルが、クラス名の複数形＋スネークケースであれば、書かなくてもOK
    protected $table = 'channels';

    // Eloquentを通して更新や登録が可能なフィールド（ホワイトリストを定義）
    protected $fillable = ['uuid', 'name'];


    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function store(string $name): self
    {
        return $this->newInstance()->create([
            'uuid' => Str::uuid(),
            'name' => $name,
        ]);
    }

    public function addFirstMember(Channel $channel, int $userId): void
    {
        $channel->users()->sync([$userId]);
    }
}
