<?php declare(strict_types=1);

namespace fmt\Argument;

trait ArgumentWithLength
{
    private int $length;

    public function getLength(): int
    {
        return $this->length;
    }
}
