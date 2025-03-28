<?php

declare(strict_types=1);

namespace Swis\JsonApi\Client\Parsers;

use Swis\JsonApi\Client\ErrorCollection;
use Swis\JsonApi\Client\Exceptions\ValidationException;

/**
 * @internal
 */
class ErrorCollectionParser
{
    private ErrorParser $errorParser;

    public function __construct(ErrorParser $errorParser)
    {
        $this->errorParser = $errorParser;
    }

    /**
     * @param  mixed  $data
     */
    public function parse($data): ErrorCollection
    {
        if (! is_array($data)) {
            throw new ValidationException(sprintf('ErrorCollection MUST be an array, "%s" given.', gettype($data)));
        }
        if (count($data) === 0) {
            throw new ValidationException('ErrorCollection cannot be empty and MUST have at least one Error object.');
        }

        return new ErrorCollection(
            array_map(
                fn ($error) => $this->errorParser->parse($error),
                $data
            )
        );
    }
}
