<?php

namespace App\Http\Resources\Tenancy;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TenantCollection extends ResourceCollection
{
    public $collects = TenantResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
