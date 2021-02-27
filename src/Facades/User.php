<?php

namespace Iaorana\Framework\Facades;

class User {
    public static function fromId(?int $id): ?\WP_User {
        $user = null;

        if (!empty($id)) {
            $user = get_userdata($id);
        }

        return $user ? $user : null;
    }

    public static function currentLoggedIn(): ?\WP_User {
        $user = wp_get_current_user();

        return $user ? $user : null;
    }
}
