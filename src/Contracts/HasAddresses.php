<?php

namespace Rinvex\Addresses\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphMany;

interface HasAddresses
{
    /**
    /**
     * Get all attached addresses to the model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function addresses(): MorphMany;
}
