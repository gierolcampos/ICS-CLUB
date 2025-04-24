<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            $model->logActivity('created');
        });

        static::updated(function (Model $model) {
            // Don't log if only timestamps were updated
            if ($model->isDirty() && 
                !($model->isDirty(['updated_at']) && count($model->getDirty()) === 1)) {
                $model->logActivity('updated');
            }
        });

        static::deleted(function (Model $model) {
            $model->logActivity('deleted');
        });
    }

    /**
     * Log activity for this model.
     *
     * @param string $event
     * @return void
     */
    public function logActivity(string $event)
    {
        $modelName = class_basename($this);
        $modelId = $this->getKey();
        
        // Generate appropriate description based on model type and event
        $description = $this->getActivityDescription($event);
        
        // Generate appropriate activity type
        $type = $this->getActivityType($event);
        
        // Get dirty fields for updates
        $properties = [];
        if ($event === 'updated') {
            $properties['old'] = array_intersect_key($this->getOriginal(), $this->getDirty());
            $properties['new'] = $this->getDirty();
        }
        
        // Store the activity log
        ActivityLog::create([
            'user_id' => auth()->id(),
            'type' => $type,
            'description' => $description,
            'model_type' => get_class($this),
            'model_id' => $modelId,
            'properties' => $properties,
        ]);
    }
    
    /**
     * Get the description for the activity.
     *
     * @param string $event
     * @return string
     */
    protected function getActivityDescription(string $event): string
    {
        $modelName = class_basename($this);
        
        switch ($event) {
            case 'created':
                return "New {$modelName} created: " . $this->getActivitySubject();
            case 'updated':
                return "{$modelName} updated: " . $this->getActivitySubject();
            case 'deleted':
                return "{$modelName} deleted: " . $this->getActivitySubject();
            default:
                return "{$modelName} {$event}: " . $this->getActivitySubject();
        }
    }
    
    /**
     * Get the activity type string.
     *
     * @param string $event
     * @return string
     */
    protected function getActivityType(string $event): string
    {
        $modelType = strtolower(class_basename($this));
        return "{$modelType}.{$event}";
    }
    
    /**
     * Get the subject string for the activity.
     *
     * @return string
     */
    protected function getActivitySubject(): string
    {
        // Models should override this to provide a meaningful description
        if (isset($this->name)) {
            return $this->name;
        }
        
        if (isset($this->title)) {
            return $this->title;
        }
        
        return "#{$this->getKey()}";
    }
} 