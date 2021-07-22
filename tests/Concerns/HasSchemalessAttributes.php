<?php

namespace Spatie\SchemalessAttributes\Tests\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Spatie\SchemalessAttributes\Casts\SchemalessAttributes;

/** @mixin \Illuminate\Database\Eloquent\Model */
trait HasSchemalessAttributes
{
    public function initializeHasSchemalessAttributes()
    {
        $this->casts['schemaless_attributes'] = SchemalessAttributes::class;
    }

    public function scopeWithExtraAttributes(): Builder
    {
        return $this->schemaless_attributes->modelScope();
    }
}
