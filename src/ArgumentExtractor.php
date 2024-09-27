<?php declare(strict_types=1);

namespace fmt;

use ArrayAccess;
use fmt\Argument\NamedArgument;
use fmt\Argument\PositionalArgument;

final class ArgumentExtractor
{
    public function extractValue(Argument $arg, ArrayAccess|array $values): mixed
    {
        if ($arg instanceof PositionalArgument) {
            if (!isset($values[$arg->getPosition()])) {
                throw new \Exception('Missing arg at:' . $arg->getPosition());
            }

            return $values[$arg->getPosition()];
        }
        if ($arg instanceof NamedArgument) {
            if (!isset($values[$arg->getName()])) {
                throw new \Exception('Missing arg named:' . $arg->getName());
            }

            return $values[$arg->getName()];
        }
        throw new \Exception('Unsupported argument type');
    }
}
