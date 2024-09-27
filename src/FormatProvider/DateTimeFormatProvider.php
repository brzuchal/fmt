<?php declare(strict_types=1);

namespace fmt\FormatProvider;

use DateTimeInterface;
use fmt\FormatProvider;

final class DateTimeFormatProvider implements FormatProvider
{
    public function format(FormatProvider $formatProvider, ?string $format, mixed $value): string
    {
        /** @var DateTimeInterface $value */
        return $value->format($format ?? \DATE_ATOM);
    }

    public function provides(mixed $value): bool
    {
        return $value instanceof DateTimeInterface;
    }
}
