<?php

namespace Iaorana\Framework\Services;

use Iaorana\Framework\Services\Executable;

abstract class DomainService implements Executable {
    /**
     * @var mixed
     */
    protected $context;

    public function __construct($context) {
        $this->context = $context;
    }

    public function getContext() {
        return $this->context;
    }
}
