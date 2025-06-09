<?php

namespace Maatwebsite\Sidebar;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory;
use Maatwebsite\Sidebar\Traits\Attributable;
use Maatwebsite\Sidebar\Traits\Authorizable;
use Maatwebsite\Sidebar\Traits\Itemable;
use Maatwebsite\Sidebar\Traits\Renderable;

class SidebarBadge
{
    use Attributable;
    use Authorizable;
    use Itemable;
    use Renderable;

    protected Factory $factory;

    protected string $view = 'sidebar::badge';

    protected string $renderType = 'badge';

    protected Container $container;

    public function __construct(Container $container, Factory $factory)
    {
        $this->container = $container;
        $this->factory = $factory;
    }

    public function init(): SidebarBadge
    {
        return $this->cleanInstance();
    }
}
