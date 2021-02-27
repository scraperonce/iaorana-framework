<?php declare(strict_types=1);

namespace Iaorana\Framework\Components;

use Iaorana\Framework\Presentations\View;
use Iaorana\Framework\Facades\Controllers\AbstractController;
use Iaorana\Framework\Facades\Parameters\ValidatableParameterInterface;
use Iaorana\Framework\Views\Helpers\Admin;

abstract class Page extends AbstractController implements Controller {

    const SLUG = 'admin_any_slug';

    /**
     * @var string|null
     */
    private $currentDir = null;

    public static function mountPoint(): array {
        return [new static(), 'route'];
    }

    public function index(ValidatableParameterInterface $parameter): void {
        $this->view('index');
    }

    public function view(string $path, $data = []): void {
        echo (new View($this, $this->getViewFullPath($path), $data))->render();
    }

    public function currentUrl(): string {
        return Admin::url(self::slug());
    }

    protected function cwd(?string $dir = null): string {
        if (!$dir) {
            if (!$this->currentDir) {
                throw new \RuntimeException('current directory is not set.');
            }

            return $this->currentDir;
        }

        $this->currentDir = $dir;
        return $dir;
    }

    protected function getViewFullPath(string $page): string {
        $dir = rtrim($this->cwd() ?? dirname(__FILE__), DIRECTORY_SEPARATOR);
        return $dir . DIRECTORY_SEPARATOR . "view" . DIRECTORY_SEPARATOR . "view-${page}.php";
    }
}
