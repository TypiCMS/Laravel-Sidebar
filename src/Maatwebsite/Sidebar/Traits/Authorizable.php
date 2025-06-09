<?php

namespace Maatwebsite\Sidebar\Traits;

trait Authorizable
{
    protected bool $authorized = true;

    /**
     * @return mixed
     */
    public function isAuthorized()
    {
        return $this->authorized;
    }

    /**
     * @return $this
     */
    public function authorize(bool $state = true)
    {
        $this->authorized = $state;

        return $this;
    }
}
