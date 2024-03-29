<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Builder;
use App\Models\BaseModel;
use App\Models\Category;
use Database\Factories\ProductFactory;

class Product extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'category_id',
        'name',
        'free_shipping',
        'description',
        'price',
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
        'category_id'   => 'integer',
        'name'          => 'string',
        'free_shipping' => 'boolean',
        'description'   => 'string',
        'price'         => 'decimal:2',
        'created_at'    => 'datetime:Y-m-d H:i:s',
        'updated_at'    => 'datetime:Y-m-d H:i:s',
        'deleted_at'    => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return ProductFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @param Builder $query
     * @param int $id
     *
     * @return Builder
     */
    public function scopeWhereById(Builder $query, int $id): Builder
    {
        return $query->where('id', $id);
    }
}
