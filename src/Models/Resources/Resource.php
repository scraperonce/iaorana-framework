<?php declare(strict_types=1);

namespace Iaorana\Framework\Models\Resources;

use Iaorana\Framework\Models\Model;
use Iaorana\Framework\ValueObjects\ValueObject;

class Resource implements ValueObject {
    /**
     * @var Model|null
     */
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function valueOf(): ?object {
        return $this->model ? $this->model->valueOf() : null;
    }
}
