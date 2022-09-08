<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class Links implements ArrayAccess, Arrayable, Jsonable, JsonSerializable
{
    /**
     * @var \Swis\JsonApi\Client\Link[]
     */
    protected $links = [];

    /**
     * @param \Swis\JsonApi\Client\Link[] $links
     */
    public function __construct(array $links)
    {
        $this->links = $links;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this->offsetSet($key, $value);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     *
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->links[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->links[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     * @param mixed $value
     */
    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->links[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $offset
     */
    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->links[$offset]);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(
            static fn (?Link $link) => $link ? $link->toArray() : null,
            $this->links
        );
    }

    /**
     * {@inheritdoc}
     *
     * @param int $options
     *
     * @return false|string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), JSON_THROW_ON_ERROR | $options);
    }

    /**
     * {@inheritdoc}
     *
     * @return object
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return (object) $this->toArray();
    }
}
