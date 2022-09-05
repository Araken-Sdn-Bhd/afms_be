<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListJobClub extends Model
{
    use HasFactory;

    protected $table = 'list_job_club';
    protected $fillable = [
        'added_by',
        'patient_id',
        'job_club',
        'location_services',
        'type_diagnosis_id',
        'category_services','services_id',
        'code_id',
        'sub_code_id',
        'complexity_services',
        'outcome',
        'medication_des',
        'status',
        'created_at'
    ];
}