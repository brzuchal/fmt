<?php declare(strict_types=1);

namespace fmt;

final class FormatSpecification
{
    public function __construct(
        private int $offset,
        private int $length,
        private Argument $arg,
        private ?string $fmt = null)
    {
        if ($fmt !== null && \strlen($fmt) === 0) {
            throw new \Exception('Format string can be null or non-empty string');
        }
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getArg(): Argument
    {
        return $this->arg;
    }

    public function getFmt(): ?string
    {
        return $this->fmt;
    }
}
