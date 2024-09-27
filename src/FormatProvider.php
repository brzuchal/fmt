<?php declare(strict_types=1);

namespace fmt;

interface FormatProvider
{
    public function format(FormatProvider $formatProvider, ?string $format, mixed $value): string;
    public function provides(mixed $value): bool;
}
