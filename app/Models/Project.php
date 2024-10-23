<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'creator_id'
    ];
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function members()
    {
        return $this->belongsToMany(User::class, Member::class);
    }
    protected static function booted()
    {
        //Điều kiện này thêm một câu lệnh WHERE vào tất cả các truy vấn của model. Nó chỉ lấy những bản ghi có cột creator_id bằng với ID của người dùng hiện tại (Auth::id()).
        // khi get request project , nhằm cho api nếu xác thực người dùng đăng nhập và chỉ được phép lấy data mà user đó đã tạo
        static::addGlobalScope('member', function (Builder $builder) {
            
            $builder->whereRelation('members','user_id',Auth::id());
        });
    }
}
