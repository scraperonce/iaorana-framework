<?php declare(strict_types=1);

namespace Iaorana\Framework\Models;

abstract class ConcreteRecord extends Record implements HavingRepository {
    /**
     * @return static
     */
    public static function fromId(int $id) {
        $query = static::getRepository()->start('select');

        $query->where()->equals('id', $id)->end();

        $record = static::getRepository()->get($query);

        return new static($record);
    }
}
