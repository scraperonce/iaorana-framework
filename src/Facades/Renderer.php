<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades;

use Iaorana\Framework\Presentations\Renderable;

class Renderer {
    public static function render(Renderable $renderable) {
        echo $renderable->render();
    }
}
