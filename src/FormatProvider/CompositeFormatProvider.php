<?php declare(strict_types=1);

namespace fmt\FormatProvider;

use fmt\FormatProvider;

final class CompositeFormatProvider implements FormatProvider
{
    private array $providers;

    public function __construct(FormatProvider ...$providers)
    {
        $this->providers = $providers;
    }

    public function format(FormatProvider $formatProvider, ?string $format, mixed $value): string
    {
        foreach ($this->providers as $provider) {
            if ($provider->provides($value)) {
                return $provider->format($formatProvider, $format, $value);
            }
        }

        throw new \Exception('Unsupported type: ' . \get_class($value));
    }

    public function provides(mixed $value): bool
    {
        foreach ($this->providers as $provider) {
            if ($provider->provides($value)) {
                return true;
            }
        }

        return false;
    }

    public function append(FormatProvider $formatProvider) : FormatProvider
    {
        $self = clone $this;
        \array_push($self->providers, $formatProvider);

        return $self;
    }
    public function prepend(FormatProvider $formatProvider) : FormatProvider
    {
        $self = clone $this;
        \array_unshift($self->providers, $formatProvider);

        return $self;
    }
}
