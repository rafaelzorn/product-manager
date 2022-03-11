<?php

namespace App\Models;

use App\Models\BaseModel;

class ProcessedFileLog extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'processed_file_id',
        'exception_message',
        'exception_trace',
    ];

     /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'processed_file_id',
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
        'processed_file_id' => 'integer',
        'exception_message' => 'string',
        'exception_trace'   => 'string',
        'created_at'        => 'datetime:Y-m-d H:i:s',
        'updated_at'        => 'datetime:Y-m-d H:i:s',
        'deleted_at'        => 'datetime:Y-m-d H:i:s',
    ];
}
