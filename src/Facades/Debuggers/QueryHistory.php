<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades\Debuggers;

use Iaorana\Framework\Framework;
use SqlFormatter;

class QueryHistory {
    private static $history = [];
    private static $callstack = [];

    public static function add(string $query) {
        if (Framework::isDebug()) {
            self::$history[] = $query;
            self::$callstack[] = (debug_backtrace())[1];
        }
    }

    public static function render(): string {
        if (Framework::isDebug()) {
            $output[] = '<ol>';
            foreach (array_reverse(self::$history) as $i => $item) {
                $output[] = '<li>';
                $output[] = "<h3><strong>on " . self::$callstack[$i]['function'] . "(): </strong>";
                $output[] = preg_replace("/.*wp-content/", "", self::$callstack[$i]['file']);
                $output[] = "(" . self::$callstack[$i]['line'] . ")</h3>";
                $output[] = SqlFormatter::format($item);
                $output[] = '</li>';
            }
            $output[] = '</ol>';

            return '<div id="__debug_history"><h2>Query History</h2>' . implode('', $output) . '</div>';
        }

        return '';
    }
}
