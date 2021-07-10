<?php

namespace App\Models;

use App\Traits\Constants\BaseConstants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alert extends Model
{
    use BaseConstants;

    const CONDITION_GREATER_THAN = '>';
    const CONDITION_LESS_THAN = '<';
    const CONDITION_EQUAL_TO = '=';
    const CONDITION_GREATER_THAN_OR_EQUAL = '>=';
    const CONDITION_LESS_THAN_OR_EQUAL = '<=';

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

    /**
     * @return HasMany
     */
    public function incidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'alert_id', 'alert_id');
    }
}
