<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metric extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'metric_name', 'app_name', 'value',
    ];
}
