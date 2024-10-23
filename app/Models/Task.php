<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'is_done',
        // 'creator_id',
        'project_id'
    ];
    protected $casts = [
        'is_done' => 'boolean'
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected static function booted()
    {
        //Điều kiện này thêm một câu lệnh WHERE vào tất cả các truy vấn của model. Nó chỉ lấy những bản ghi có cột creator_id bằng với ID của người dùng hiện tại (Auth::id()).
        // khi get request tasks
        // static::addGlobalScope('create', function (Builder $builder) {
        //     $builder->where('creator_id', Auth::id());
        // });
        static::addGlobalScope('member', function (Builder $builder) {
            $builder->where('creator_id', Auth::id())
                ->orWhereIn('project_id', Auth::user()->memberships->pluck('id'));
        });
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
