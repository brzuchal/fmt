<?php declare(strict_types=1);

namespace fmt\FormatProvider;

use fmt\FormatProvider;

final class StringFormatProvider implements FormatProvider
{
    public function format(FormatProvider $formatProvider, ?string $format, mixed $value): string
    {
        return \sprintf("%s", $value);
    }

    public function provides(mixed $value): bool
    {
        return \is_string($value);
    }
}
