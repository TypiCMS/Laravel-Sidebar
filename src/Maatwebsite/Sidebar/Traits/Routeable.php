<?php

namespace Maatwebsite\Sidebar\Traits;

trait Routeable
{
    public function route(string $route, array $params = []): self
    {
        return $this->setAttribute('route', route($route, $params));
    }

    public function getRoute($value): string
    {
        // No need to have route, when we have children
        if ($this->hasItems()) {
            return '#';
        }

        if (!$value) {
            $value = route('acp.' . $this->getRawAttribute('name') . '.index');
        }

        return $value;
    }
}
