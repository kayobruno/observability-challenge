<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'app_name', 'title', 'description', 'enabled', 'metric', 'condition', 'threshold'
    ];

    /**
     * @var string
     */
    protected $primaryKey = 'alert_id';
}
