<?php declare(strict_types=1);

namespace Iaorana\Framework\Presentations;

use Iaorana\Framework\Components\Controller;
use Iaorana\Framework\Facades\Controllers\ControllerInterface;
use Iaorana\Framework\Facades\Debuggers\QueryHistory;
use Iaorana\Framework\Framework;

class View implements Renderable {

    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var ViewAttributes
     */
    private $data;

    public function __construct(ControllerInterface $controller, string $path, $data = null) {
        $this->controller = $controller;
        $this->path = $path;

        if ($data instanceof ViewAttributes) {
            $this->data = $data;
        } elseif (is_array($data)) {
            $this->data = new ViewAttributes($data);
        } else {
            $this->data = $data;
        }
    }

    public function render(): string {
        $page = $this->controller;
        $data = $this->data;
        ob_start();
        include $this->path;

        if (Framework::isDebug()) {
            echo QueryHistory::render();
        }

        $buffer = ob_get_clean();

        return $buffer;
    }
}
