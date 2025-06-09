<?php

namespace Maatwebsite\Sidebar;

use Closure;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Routing\ResolvesRouteDependencies;
use Illuminate\Support\Collection;
use Maatwebsite\Sidebar\Traits\Attributable;
use Maatwebsite\Sidebar\Traits\Authorizable;
use Maatwebsite\Sidebar\Traits\Itemable;
use Maatwebsite\Sidebar\Traits\Renderable;
use Maatwebsite\Sidebar\Traits\Routeable;
use ReflectionException;
use ReflectionFunction;

class SidebarItem
{
    use Attributable;
    use Authorizable;
    use Itemable;
    use Renderable;
    use ResolvesRouteDependencies;
    use Routeable;

    protected Factory $factory;

    protected string $id;

    protected string $icon;

    protected int $weight;

    protected string $view = 'sidebar::item';

    protected string $renderType = 'item';

    private SidebarBadge $badgeGenerator;

    public array $badges = [];

    public array $appends = [];

    private Container $container;

    private SidebarAppend $appendGenerator;

    private Request $request;

    public function __construct(
        Container $container,
        Request $request,
        Factory $factory,
        SidebarBadge $badgeGenerator,
        SidebarAppend $appendGenerator
    ) {
        $this->container = $container;
        $this->factory = $factory;
        $this->badgeGenerator = $badgeGenerator;
        $this->appendGenerator = $appendGenerator;
        $this->request = $request;

        $this->items = new Collection();
    }

    public function init($name): SidebarItem
    {
        $instance = $this->cleanInstance();
        $instance->setAttribute('name', $name);
        $instance->setAttribute('weight', 1);
        $instance->items = new Collection();

        return $instance;
    }

    public function isActiveWhen(bool $condition = true): SidebarItem
    {
        $this->setAttribute('active', $condition);

        return $this;
    }

    public function badge(?Closure $callback = null, bool $color = false): SidebarBadge
    {
        $badge = $this->badgeGenerator->init();

        if ($callback instanceof Closure) {
            $parameters = $this->resolveMethodDependencies(
                ['badge' => $badge],
                new ReflectionFunction($callback)
            );
            call_user_func_array($callback, $parameters);
        } elseif (is_string($callback)) {
            $badge->setAttribute('value', $callback);
            if ($color) {
                $badge->setAttribute('color', $color);
            }
        }

        $this->badges[] = $badge;

        return $badge;
    }

    public function hasBadge(): bool
    {
        return count($this->badges) > 0;
    }

    /**
     * @throws ReflectionException
     */
    public function append(null|callable|string $callback = null): SidebarAppend
    {
        $append = $this->appendGenerator->init();

        if ($callback instanceof Closure) {
            $parameters = $this->resolveMethodDependencies(
                ['append' => $append],
                new ReflectionFunction($callback)
            );
            call_user_func_array($callback, $parameters);
        } elseif (is_string($callback)) {
            // just a route
            $append->route($callback);
        }

        $this->appends[] = $append;

        return $append;
    }

    public function hasAppend(): bool
    {
        return count($this->appends) > 0;
    }

    public function getItem(): SidebarItem
    {
        return $this;
    }

    public function getState(?string $value = null): ?string
    {
        if (!$value && $this->checkActiveState()) {
            return 'active';
        }

        return $value;
    }

    protected function checkActiveState(): bool
    {
        // Check if one of the children is active
        foreach ($this->items as $item) {
            if ($item->checkActiveState()) {
                return true;
            }
        }

        // If the active state was manually set
        if (!is_null($this->getAttribute('active'))) {
            return $this->getAttribute('active');
        }

        $path = mb_ltrim(str_replace(url('/'), '', $this->getAttribute('route')), '/');

        return $this->request->is(
            $path,
            $path . '/*'
        );
    }
}
