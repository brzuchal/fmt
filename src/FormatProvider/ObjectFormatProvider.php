<?php declare(strict_types=1);

namespace fmt\FormatProvider;

use fmt\FormatProvider;

final class ObjectFormatProvider implements FormatProvider
{
    public function format(FormatProvider $formatProvider, ?string $format, mixed $value): string
    {
        return \get_class($value);
    }

    public function provides(mixed $value): bool
    {
        return \is_object($value);
    }
}
