<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyField extends Model
{
    
    protected $table = 'study_fields';
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;
    protected $fillable = [
        'name',
    ];
    // public function study_programs(): HasMany
    // {
    //     return $this->hasMany(StudyProgram::class, 'study_field_id', 'id');
    // }
}
