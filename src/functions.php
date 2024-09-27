<?php declare(strict_types=1);

namespace fmt;

function string_formatter(FormatProvider ...$formatProviders): StringFormatter
{
    static $factory;
    $factory = $factory ?? new FormatProviderFactory();
    $currentFormatProvider = $factory->createDefaultFormatProvider();
    foreach ($formatProviders as $formatProvider) {
        $currentFormatProvider = $currentFormatProvider->prepend($formatProvider);
    }
    return new StringFormatter($currentFormatProvider);
}

function string_format(string $format, ...$values): string
{
    return string_formatter()->format($format, ...$values);
}

function string_format_array(string $format, array $values): string
{
    return string_formatter()->formatValues($format, $values);
}

function print_format(string $format, ...$values): void
{
    print string_formatter()->format($format, ...$values);
}
