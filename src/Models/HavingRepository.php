<?php declare(strict_types=1);

namespace Iaorana\Framework\Models;

use Iaorana\Framework\Models\Types\Type;
use Iaorana\Framework\Repositories\Repository;

interface HavingRepository {
    public static function getRepository(): Repository;

    /**
     * @return static
     */
    public static function fromId(int $id);
    public function set(string $key, $value, string $type = Type::AS_IS): void;
}
