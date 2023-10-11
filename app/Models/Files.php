<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;
    protected $table = 'backups';
    protected $primaryKey = 'backup_id';
    protected $fillable = ['user_id','file_name','file_path','timestamp'];
}
