<?php declare(strict_types=1);

namespace Iaorana\Framework\Frontend;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Iaorana\Framework\Facades\Controllers\AbstractController;
use Iaorana\Framework\Facades\Controllers\ControllerInterface;
use Iaorana\Framework\Facades\Parameters\ValidatableParameterInterface;

abstract class Action extends AbstractController {

    const SLUG = 'any_slug';

    /**
     * @var Collection
     */
    public $data;

    public static function do(): ControllerInterface {
        $action = new static();
        $action->route();
        return $action;
    }

    public function __construct() {
        parent::__construct();

        $this->data = new ArrayCollection();
    }

    public function index(ValidatableParameterInterface $parameter): void {
        // do nothing
    }
}
