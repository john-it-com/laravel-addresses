<?php

declare(strict_types=1);

namespace Rinvex\Addresses\Traits;

use Illuminate\Database\Eloquent\Relations\MorphOne;

trait InteractsWithAddress
{
    /**
     * Register a deleted model event with the dispatcher.
     *
     * @param \Closure|string $callback
     *
     * @return void
     */
    abstract public static function deleted($callback);

    /**
     * Define a polymorphic one-to-one relationship.
     *
     * @param  string  $related
     * @param  string  $name
     * @param  string|null  $type
     * @param  string|null  $id
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    abstract public function morphOne($related, $name, $type = null, $id = null, $localKey = null);

    /**
     * Boot the addressable trait for the model.
     *
     * @return void
     */
    public static function bootHasAddress()
    {
        static::deleting(function (self $model) {
            if($model->address !== null) {
                $model->address->forceDelete();
            }
        });
    }

    /**
     * Get all attached addresses to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function address(): MorphOne
    {
        return $this->morphOne(config('rinvex.addresses.models.address'), 'addressable', 'addressable_type', 'addressable_id');
    }

}