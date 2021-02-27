<?php declare(strict_types=1);

namespace Iaorana\Framework\Presentations;

interface Renderable {
    public function render(): string;
}
