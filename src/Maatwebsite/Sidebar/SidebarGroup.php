<?php

namespace Maatwebsite\Sidebar;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Routing\ResolvesRouteDependencies;
use Illuminate\Support\Collection;
use Maatwebsite\Sidebar\Traits\Attributable;
use Maatwebsite\Sidebar\Traits\Authorizable;
use Maatwebsite\Sidebar\Traits\Itemable;
use Maatwebsite\Sidebar\Traits\Renderable;

class SidebarGroup
{
    use Attributable;
    use Authorizable;
    use Itemable;
    use Renderable;
    use ResolvesRouteDependencies;

    private Container $container;

    public string $id;

    public int $weight;

    protected Factory $factory;

    protected SidebarItem $item;

    protected string $view = 'sidebar::group';

    protected string $renderType = 'group';

    private bool $hideHeading;

    public function __construct(Container $container, Factory $factory, SidebarItem $item)
    {
        $this->container = $container;
        $this->factory = $factory;
        $this->item = $item;
    }

    public function init(string $name): SidebarGroup
    {
        // Reset the object
        $instance = $this->cleanInstance();
        $instance->setAttribute('name', $name);
        $instance->setAttribute('weight', 1);
        $instance->items = new Collection();

        return $instance;
    }

    public function hideHeading(bool $state = true): bool
    {
        $this->hideHeading = $state;

        return false;
    }

    public function shouldShowHeading(): bool
    {
        return !$this->hideHeading;
    }

    public function getItem(): SidebarItem
    {
        return $this->item;
    }
}
