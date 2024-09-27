<?php declare(strict_types=1);

namespace fmt\FormatProvider;

use fmt\FormatProvider;

final class NumberFormatProvider implements FormatProvider
{
    public function format(FormatProvider $formatProvider, ?string $format, mixed $value): string
    {
        if ($format === null) {
            $format = 'd';
        } elseif (!\in_array($format[-1], ['d', 'f'], true)) {
            throw new \Exception('Unsupported format');
        }

        return \sprintf("%" . $format, $value);
    }

    public function provides(mixed $value): bool
    {
        return \is_float($value) || \is_int($value);
    }
}
