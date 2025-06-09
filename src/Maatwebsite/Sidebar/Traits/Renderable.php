<?php

namespace Maatwebsite\Sidebar\Traits;

trait Renderable
{
    public function render()
    {
        if ($this->isAuthorized()) {
            return $this->factory->make($this->getView(), [
                $this->getRenderType() => $this,
            ])->render();
        }
    }

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    public function setView($view): self
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRenderType()
    {
        return $this->renderType;
    }

    public function __toString()
    {
        return $this->render();
    }
}
