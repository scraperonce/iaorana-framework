<?php declare(strict_types=1);

namespace Iaorana\Framework\Repositories\QueryBuilder\Values;

class LikeValue implements SelfEscapingValue {

    const MATCH_BOTH = 0;
    const MATCH_LEFT = 1;
    const MATCH_RIGHT = 2;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $match_rule;

    public function __construct(string $val, int $match_rule = self::MATCH_BOTH) {
        $this->value = $val;
        $this->match_rule = $match_rule;
    }

    public function escape(): string {
        global $wpdb;

        return implode('', [
            ($this->match_rule !== self::MATCH_RIGHT ? '%' : ''),
            $wpdb->esc_like($this->value),
            ($this->match_rule !== self::MATCH_LEFT ? '%' : '')
        ]);
    }

    public function valueOf(): string {
        return $this->value;
    }
}
