<?php

namespace Rinvex\Addresses\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface HasAddress
{
    /**
     * Get all attached addresses to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function address(): MorphOne;
}
