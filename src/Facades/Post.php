<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades;

class Post {

    protected static $cache_page = [];
    protected static $cache_post = [];

    protected static $default_args = [
        "nopaging" => true,
        'page' => 0,
        'posts_per_page'=> -1,
        'post_status' => [
            'any',
            'trash',
            'auto-draft',
        ],
        'orderby' => 'p', // 投稿ID
        'order' => 'DESC',
    ];

    public static function fromSlug($slug): ?\WP_Post {
        if (empty($slug)) {
            return null;
        }

        $slug = strval($slug);

        if (array_key_exists($slug, self::$cache_page)) {
            return self::$cache_page[$slug];
        }

        $result = self::query([
            "post_type" => "page",
            "pagename" => $slug,
        ]);

        self::$cache_page[$slug] = !empty($result) ? reset($result) : null;

        return self::$cache_page[$slug];
    }

    public static function fromId($id, string $type = null): ?\WP_Post {
        $type = $type ? $type : 'page';

        if (empty($id)) {
            return null;
        }

        $id = intval($id, 10);

        $key = "${id}_${type}";

        if (array_key_exists($key, self::$cache_post)) {
            return self::$cache_post[$key];
        }

        $result = self::query([
            "post_type" => $type,
            "p" => $id,
        ]);

        self::$cache_post[$key] = !empty($result) ? reset($result) : null;

        return self::$cache_post[$key];
    }

    /**
     * @return \WP_Post[]
     */
    public static function getChildren($id, string $type = null): array {
        $type = $type ? $type : 'page';
 
        if (empty($id)) {
            return [];
        }
 
        $id = intval($id, 10);

        return self::query([
            "post_type" => $type,
            "post_parent" => $id,
        ]);
    }

    /**
     * @param array $query_args array of query vars
     * @return \WP_Post[]
     */
    public static function query(array $query_args): array {
        wp_reset_query();

        $result = new \WP_Query(array_merge(self::$default_args, $query_args));

        $posts = $result->have_posts() ? $result->posts : [];

        $result->reset_postdata();

        return $posts;
    }

    public static function current(): ?\WP_Post {
        return self::fromId(get_queried_object_id(), 'any');
    }
}
