<?php declare(strict_types=1);

namespace Iaorana\Framework\Models\Types;

class Type {
    const AS_IS = null;
    const STRING = 'string';
    const INT = 'integer';
    const FLOAT = 'float';

    public static function typecast($value, $type = self::AS_IS) {
        if ($type === Type::AS_IS) {
            return $value;
        } elseif ($type === Type::STRING) {
            return $value !== null ? strval($value) : '';
        } elseif ($type === Type::INT) {
            $i = intval($value, 10);

            return $i === false ? null : $i;
        } elseif ($type === Type::FLOAT) {
            return floatval($value);
        }

        return $value;
    }
}
