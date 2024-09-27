<?php declare(strict_types=1);

namespace fmt\FormatProvider;

use Ramsey\Uuid\UuidInterface;
use fmt\FormatProvider;

final class UuidFormatProvider implements FormatProvider
{
    public function format(FormatProvider $formatProvider, ?string $format, mixed $value): string
    {
        if ($format === 'H') {
            /** @var UuidInterface $value */
            return $value->getHex()->toString();
        } elseif ($format === 'B') {
            /** @var UuidInterface $value */
            return $value->getBytes();
        }

        /** @var UuidInterface $value */
        return $value->toString();
    }

    public function provides(mixed $value): bool
    {
        return $value instanceof UuidInterface;
    }
}
