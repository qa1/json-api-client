<?php

namespace Swis\JsonApi\Client\Interfaces;

use Swis\JsonApi\Client\Collection;
use Swis\JsonApi\Client\Errors\ErrorCollection;

interface DocumentInterface extends \JsonSerializable
{
    /**
     * @return \Swis\JsonApi\Client\Interfaces\DataInterface
     */
    public function getData();

    /**
     * @return \Swis\JsonApi\Client\Errors\ErrorCollection
     */
    public function getErrors(): ErrorCollection;

    /**
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * @return \Swis\JsonApi\Client\Meta|null
     */
    public function getMeta();

    /**
     * @return mixed
     */
    public function getLinks(): array;

    /**
     * @return mixed
     */
    public function getIncluded(): Collection;

    /**
     * @return \Swis\JsonApi\Client\Jsonapi|null
     */
    public function getJsonapi();

    /**
     * @return array
     */
    public function toArray(): array;
}
