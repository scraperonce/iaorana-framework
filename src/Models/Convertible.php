<?php declare(strict_types=1);

namespace Iaorana\Framework\Models;

use Doctrine\Common\Collections\Collection;

interface Convertible {
    /**
     * @param object[] $records
     * @return Collection<static>
     */
    public static function fromArray(array $records): Collection;

    /**
     * @return static
     */
    public static function from(object $record);
}
