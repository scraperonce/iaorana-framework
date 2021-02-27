<?php declare(strict_types=1);

namespace Iaorana\Framework\Views\Helpers;

use Iaorana\Framework\Facades\Sanitize;

class Admin {
    /**
     * @param $slug
     */
    public static function url(?string $slug = null) {
        if ($slug) {
            return admin_url('admin.php?page=' . Sanitize::h("${slug}"));
        } else {
            return admin_url();
        }
    }

    /**
     * @param $slug
     */
    public static function pageUrl($id) {
        return admin_url('post.php?action=edit&post=' . Sanitize::h("${id}"));
    }

    public static function isOpened() {
        return is_admin();
    }
}
