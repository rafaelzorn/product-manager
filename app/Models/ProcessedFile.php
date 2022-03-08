<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProcessedFileStatusEnum;

class ProcessedFile extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
}
