<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];

    public const STATUS_SELECT = [
        'todo' => 'Todo',
        'in progress' => 'In Progress',
        'done' => 'Done',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public $fillable = [
        'title',
        'parent_id',
        'user_id',
        'status',
    ];

    /**
     * Get the parent that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    /**
     * Get all of the tasks for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class,'parent_id');
    }

    /**
     * Get all of the tasks for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subTasks(): HasMany
    {
        return $this->hasMany(Task::class,'parent_id')->with('tasks');
    }

    /**
     * Get the user that owns the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the taskNotifications for the Task
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskNotifications(): HasMany
    {
        return $this->hasMany(TaskNotification::class);
    }



    /**
     * Holds the methods' names of Eloquent Relations
     * to fall on delete cascade or on restoring
     *
     * @var array
     */
    protected static $relations_to_cascade = [
        'taskNotifications',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function($resource)
        {
            foreach (static::$relations_to_cascade as $relation) {
                $resource->{$relation}()->delete();
            }
            // $resource->parent_id = null;
            foreach ($resource->tasks()->get() as $item) {
                $item->parent_id = null;
                $item->update();
            }
            foreach ($resource->subTasks()->get() as $item) {
                $item->parent_id = null;
                $item->update();
            }
            foreach ($resource->parent()->get() as $item) {
                $item->parent_id = null;
                $item->update();
            }
        });
        static::restoring(function($resource) {
            foreach (static::$relations_to_cascade as $relation) {
                foreach ($resource->{$relation}()->withTrashed()->get() as $item) {
                    $item->restore();
                }
            }
        });
    }
}
