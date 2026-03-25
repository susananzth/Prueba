<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'position',
        'phone',
        'project_id',
        'status',
    ];

    protected $casts = [
        'status'     => 'boolean',
        'project_id' => 'integer',
    ];

    // ─── Relaciones ──────────────────────────────────────────

    /**
     * Proyecto principal asignado al trabajador.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Contratos del trabajador (puede tener varios).
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Contrato vigente a la fecha actual.
     */
    public function activeContract()
    {
        return $this->hasOne(Contract::class)
            ->where('status', 'active')
            ->where('start_date', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', now()->toDateString());
            });
    }

    // ─── Scopes ──────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
