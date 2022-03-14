<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BaseModel;
use App\Enums\ProcessedFileStatusEnum;
use Database\Factories\ProcessedFileFactory;

class ProcessedFile extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'original_filename',
        'stored_filename',
        'status',
    ];

     /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'original_filename' => 'string',
        'stored_filename'   => 'string',
        'status'            => ProcessedFileStatusEnum::class,
        'created_at'        => 'datetime:Y-m-d H:i:s',
        'updated_at'        => 'datetime:Y-m-d H:i:s',
        'deleted_at'        => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ProcessedFileFactory::new();
    }
}
