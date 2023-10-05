<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;//この部分がDBのtasksテーブルからTaskモデルにデータを渡している
    
    protected $fillable = ['title', 'memo', 'state', 'category_id', 'user_id'];

    
    public function getByLimit(int $limit_count = 10)
    {
        // updated_at(更新した時刻)で降順(DESC)に並べ，limitで件数制限をかける
        return $this->orderBy('updated_at', 'DESC')->limit($limit_count)->get();
    }
    
    
    public function getPaginateByLimit(int $limit_count =10)
    {
        return $this->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function category(){
        return $this->belongsTo(Category::class);
    }
    
    
}
