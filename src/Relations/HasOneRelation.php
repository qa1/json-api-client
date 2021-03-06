<?php

namespace Swis\JsonApi\Client\Relations;

use Swis\JsonApi\Client\Interfaces\TypedRelationInterface;
use Swis\JsonApi\Client\Traits\HasType;

class HasOneRelation extends AbstractOneRelation implements TypedRelationInterface
{
    use HasType;

    /**
     * @param string $type
     */
    public function __construct(string $type)
    {
        $this->setType($type);
    }
}
