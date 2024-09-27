<?php declare(strict_types=1);

namespace fmt;

class FormatProviderFactory
{
    private FormatProvider $defaultFormatProvider;

    public function __construct()
    {
        $this->defaultFormatProvider = new FormatProvider\CompositeFormatProvider(
                $this->createNumberFormatProvider(),
                $this->createStringFormatProvider(),
                $this->createDateTimeFormatProvider(),
                $this->createUuidFormatProvider(),
                $this->createObjectFormatProvider(),
            );
    }

    public function createDefaultFormatProvider(): FormatProvider\CompositeFormatProvider
    {
        return $this->defaultFormatProvider;
    }

    private function createNumberFormatProvider(): FormatProvider\NumberFormatProvider
    {
        return new FormatProvider\NumberFormatProvider();
    }

    private function createStringFormatProvider(): FormatProvider\StringFormatProvider
    {
        return new FormatProvider\StringFormatProvider();
    }

    private function createDateTimeFormatProvider(): FormatProvider\DateTimeFormatProvider
    {
        return new FormatProvider\DateTimeFormatProvider();
    }

    private function createUuidFormatProvider(): FormatProvider\UuidFormatProvider
    {
        return new FormatProvider\UuidFormatProvider();
    }

    private function createObjectFormatProvider(): FormatProvider\ObjectFormatProvider
    {
        return new FormatProvider\ObjectFormatProvider();
    }
}
