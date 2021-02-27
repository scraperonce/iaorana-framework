<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Controllers;

use Iaorana\Framework\Exceptions\RoutingException;
use Iaorana\Framework\Exceptions\Throwable;
use Iaorana\Framework\Facades\Parameters\ValidatableParameterInterface;
use Iaorana\Framework\Framework;
use Iaorana\Framework\Validators\ValidatableCollection;
use Iaorana\Framework\Validators\ValidatorCollection;
use Iaorana\Framework\Views\Helpers\ErrorDisplay;
use Iaorana\Framework\Views\Helpers\Flash;

abstract class AbstractController implements ControllerInterface {
    /**
     * @var sring Post Slug
     */
    const SLUG = 'slug';

    /**
     * @var ValidatorCollection<Parameter>
     */
    protected $parameters;

    /**
     * @var Flash
     */
    public $flash;

    public static function slug(): string {
        return static::SLUG;
    }

    public function __construct() {
        if (!$this->parameters) {
            $this->parameters = new ValidatorCollection([]);
        }

        $this->configure();

        $this->flash = new Flash($this->getValidatorCollection());
    }

    public function parameter(string $name): ValidatableParameterInterface {
        $parameter = $this->getValidatorCollection()->get($name);
        if (!$parameter) {
            throw new RoutingException("Parameter '${name}' is not assigned.");
        }

        return $parameter;
    }

    public function route(): void {
        try {
            foreach ($this->getValidatorCollection() as $key => $param) {
                if ($param->inAction()) {
                    if (method_exists($this, $key)) {
                        $this->$key($param);
                    } else {
                        throw new RoutingException("Parameter '${key}' is assigned, but action method '${key}' is not created.");
                    }
                    return;
                }
            }

            if (!$this->getValidatorCollection()->get('index')) {
                throw new RoutingException("Paramter 'index' must be assigned.");
            }

            $this->delegate('index');
        } catch (Throwable $e) {
            if (Framework::isDebug()) {
                echo (new ErrorDisplay($e))->render();
                exit;
            } else {
                $this->flash->error($e->getMessage());
                $this->delegate('index');
            }
        }
    }

    public function delegate(string $key) {
        static $loop_jockey = 0;

        if (++$loop_jockey > 5) {
            throw new RoutingException("Possible infinite delegating detected. Check your delegate('${key}') call.");
        }

        $param = $this->getValidatorCollection()->get($key);

        if (method_exists($this, $key)) {
            return $this->$key($param);
        } else {
            throw new RoutingException("Parameter '${key}' is assigned, but action method '${key}' is not created.");
        }
    }

    public function __get(string $name) {
        // ~~Parameter
        if ($this->getParameterName($name)) {
            return $this->parameter($this->getParameterName($name));
        }

        return null;
    }

    private function getParameterName(string $name): ?string {
        if (strpos($name, 'Parameter') > 1) {
            return str_replace('Parameter', '', $name);
        }

        return null;
    }

    public function getValidatorCollection(): ValidatableCollection {
        return $this->parameters;
    }

    public function flash(): string {
        return $this->flash->render();
    }

    protected function configure(): void {
        // configure
    }

    abstract public function index(ValidatableParameterInterface $parameter): void;
}
