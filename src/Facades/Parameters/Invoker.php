<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Parameters;

interface Invoker {
    public function invoke(string $klass);
}
