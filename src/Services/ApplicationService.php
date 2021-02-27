<?php

namespace Iaorana\Framework\Services;

use Iaorana\Framework\Facades\Parameters\Invoker;
use Iaorana\Framework\Facades\Parameters\Parameter;
use Iaorana\Framework\Services\Executable;

abstract class ApplicationService implements Executable {
    /**
     * @var Parameter
     */
    protected $parameter;

    public function __construct(Invoker $parameter) {
        $this->parameter = $parameter;
    }

    protected function getParameter(): Parameter {
        /** @var Parameter */
        $param = $this->parameter;

        return $param;
    }
}
