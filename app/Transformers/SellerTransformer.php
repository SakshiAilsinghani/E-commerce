<?php

namespace App\Transformers;

use App\Models\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identifier' => (int)$seller->id,
            'name' => $seller->name,
            'email' => $seller->email,
            'isVerified' => (boolean)$seller->isVerified(),
            'creationDate' => $seller->created_at,
            'lastChange' => $seller->updated_at,
            'deletedDate' => isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
        ];
    }
}
