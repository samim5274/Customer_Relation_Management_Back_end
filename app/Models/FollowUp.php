<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'title',
        'note',
        'status',
        'priority',
        'follow_up_date',
        'last_contacted_at',
        'contact_type',
        'outcome',
        'deal_amount',
        'is_converted',
        'reminder_sent',
        'meta',
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
        'last_contacted_at' => 'datetime',
        'is_converted' => 'boolean',
        'reminder_sent' => 'boolean',
        'meta' => 'array',
        'deal_amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    // Customer relation
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Assigned user (agent / admin)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes (Very Useful)
    |--------------------------------------------------------------------------
    */

    // Pending follow-ups // FollowUp::pending()->get();
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Today's follow-ups // FollowUp::today()->get();
    public function scopeToday($query)
    {
        return $query->whereDate('follow_up_date', now()->toDateString());
    }

    // Overdue follow-ups // FollowUp::overdue()->get();
    public function scopeOverdue($query)
    {
        return $query->where('follow_up_date', '<', now())
                     ->where('status', '!=', 'closed');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (Optional UI helper)
    |--------------------------------------------------------------------------
    */

    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'contacted' => 'bg-blue-100 text-blue-800',
            'interested' => 'bg-green-100 text-green-800',
            'not_interested' => 'bg-red-100 text-red-800',
            'closed' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // <span :class="followup.status_color">
    // {{ followup.status }}
    // </span>
}
