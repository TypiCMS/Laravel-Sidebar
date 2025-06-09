<?php

namespace Maatwebsite\Sidebar\Traits;

use Illuminate\Support\Str;

trait Attributable
{
    /**
     * @var array
     */
    protected $attributes = [];

    public function cleanInstance()
    {
        $instance = $this->container->make(get_class($this));

        return $instance;
    }

    /**
     * Set attribute.
     *
     * @return $this
     */
    public function setAttribute($attribute, $value)
    {
        $this->attributes[$attribute] = $value;

        return $this;
    }

    /**
     * Get attribute.
     *
     * @param null $value
     * @return null|mixed
     */
    public function getAttribute($attribute, $value = null)
    {
        $value = $this->getRawAttribute($attribute, $value);

        if ($this->hasMutator($attribute)) {
            return $this->mutate($attribute, $value);
        }

        return $value;
    }

    /**
     * Get the raw attribute value.
     *
     * @param null|mixed $value
     */
    public function getRawAttribute($attribute, $value = null)
    {
        if (isset($this->attributes[$attribute])) {
            $value = $this->attributes[$attribute];
        }

        return $value;
    }

    public function hasMutator($attribute): bool
    {
        $method = $this->getMutateMethod($attribute);

        return method_exists($this, $method);
    }

    /**
     * Mutate the attribute value.
     *
     * @return mixed
     */
    public function mutate($attribute, $value)
    {
        $method = $this->getMutateMethod($attribute);

        return $this->{$method}($value);
    }

    protected function getMutateMethod($attribute): string
    {
        return 'get' . Str::studly($attribute);
    }

    /**
     * Magic setter.
     *
     * @return mixed
     */
    public function __set($attribute, $value)
    {
        return $this->setAttribute($attribute, $value);
    }

    /**
     * Magic getter.
     *
     * @return null|mixed
     */
    public function __get($attribute)
    {
        return $this->getAttribute($attribute);
    }

    /**
     * Check if attribute isset.
     *
     * @return null|mixed
     */
    public function __isset($attribute)
    {
        return isset($this->attributes[$attribute]) ? true : false;
    }

    /**
     * Magic call.
     *
     * @return Attributable
     */
    public function __call($method, $params)
    {
        return $this->setAttribute($method, head($params));
    }
}
