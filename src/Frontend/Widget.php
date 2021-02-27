<?php declare(strict_types=1);

namespace Iaorana\Framework\Frontend;

use Doctrine\Common\Collections\ArrayCollection;
use Iaorana\Framework\Facades\Sanitize;
use Iaorana\Framework\Presentations\Renderable;

abstract class Widget implements Renderable {

    /**
     * @var array
     */
    protected static $default_options = [
        'class_names' => [],
    ];

    /**
     * @var string
     */
    protected $value = '';

    /**
     * @var Collection<string>
     */
    protected $options;

    public function __construct(string $value, array $options = []) {
        $this->value = $value;
        $this->options = new ArrayCollection(array_replace_recursive(
            self::$default_options,
            static::$default_options,
            $options,
        ));
    }

    abstract public function render(): string;

    protected function buildAttr(): string {
        return implode('', [
            $this->generateAttrString('class', $this->mergeClassNames($this->options->get('class_names'))),
        ]);
    }

    protected function generateAttrString(string $key, ?string $value): string {
        if (empty($value)) {
            return '';
        }

        return "${key}=\"" . Sanitize::h($value) . "\"";
    }

    private function mergeClassNames(array $class_names): string {
        return implode(' ', array_filter($class_names, function ($v) {
            return !is_null($v) && $v !== '';
        }));
    }
}
