<?php declare(strict_types=1);

namespace fmt\Argument;

use fmt\Argument;

final class PositionalArgument implements Argument
{
    use ArgumentWithLength;
    public int $position;

    public function __construct(int $position)
    {
        $this->position = $position;
        $this->length = $position < 10 ? 1 : \strlen((string) $position);
    }

    public function getPosition(): int
    {
        return $this->position;
    }
}
