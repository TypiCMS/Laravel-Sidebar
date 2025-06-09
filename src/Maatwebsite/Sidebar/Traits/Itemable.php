<?php

namespace Maatwebsite\Sidebar\Traits;

use Closure;
use Illuminate\Support\Collection;
use Maatwebsite\Sidebar\SidebarItem;
use ReflectionFunction;

trait Itemable
{
    /**
     * @var Collection
     */
    public $items = [];

    public function addItem(string $name, ?Closure $callback = null): SidebarItem
    {
        $item = $this->getItem()->init($name);

        if ($callback && $callback instanceof Closure) {
            $parameters = $this->resolveMethodDependencies(
                ['item' => $item],
                new ReflectionFunction($callback)
            );

            call_user_func_array($callback, $parameters);
        }

        // Add the new item to the array
        if (!empty($item)) {
            $this->items->push($item);
        }

        // Return the item object
        return $item;
    }

    public function hasItems(): bool
    {
        return count($this->items) > 0 ? true : false;
    }

    public function getItems(): Collection
    {
        return $this->items->sortBy('weight');
    }
}
