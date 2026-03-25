<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'employee_id', 'project_id', 'start_date', 'end_date', 'salary', 'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'salary'     => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
