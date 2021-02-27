<?php declare(strict_types=1);

namespace Iaorana\Framework\Views\Helpers;

use Iaorana\Framework\Exceptions\AuthorizationException;
use Iaorana\Framework\Exceptions\DatabaseException;
use Iaorana\Framework\Exceptions\RequestException;
use Iaorana\Framework\Exceptions\Throwable;
use Iaorana\Framework\Facades\Sanitize;
use Iaorana\Framework\Presentations\Renderable;
use SqlFormatter;

class ErrorDisplay implements Renderable {
    /**
     * @var Throwable
     */
    private $e;

    public function __construct(Throwable $e) {
        $this->e = $e;
    }

    public function render(): string {
        if ($this->e instanceof AuthorizationException) {
            return $this->renderAuthFail();
        }

        if ($this->e instanceof RequestException) {
            return $this->renderBadRequest();
        }

        return $this->renderFatal();
    }

    private function renderAuthFail(): string {
        ob_start();
        ?>
        <div class="notice notice-error" style="margin-top: 50px; padding-bottom: 20px;">
            <h2 style="font-size: xx-large; margin-bottom: 1.5em">Access Denied</h2>
            <h3 style="color: #dc3232; margin-bottom: 1.5em"><?= Sanitize::h($this->readMessage($this->e)) ?></h3>
        </div>

        <?php
        return ob_get_clean();
    }

    private function renderBadRequest(): string {
        ob_start();
        ?>
        <div class="notice notice-error" style="margin-top: 50px; padding-bottom: 20px;">
            <h2 style="font-size: xx-large; margin-bottom: 1.5em">Bad Request</h2>
            <h3 style="color: #dc3232; margin-bottom: 1.5em"><?= Sanitize::h($this->readMessage($this->e)) ?></h3>
        </div>

        <?php
        return ob_get_clean();
    }

    private function renderFatal(): string {
        ob_start();
        ?>
        <div class="notice notice-error" style="margin-top: 50px; padding-bottom: 20px;">
            <p style="opacity: .7; text-align: right; font-style: italic !important;"><?= get_class($this->e); ?></p>
            <h2 style="font-size: xx-large; margin-bottom: 1.5em">
                Fatal Error <small style="opacity: .7">code: <?= Sanitize::h(strval($this->e->getCode())) ?></small>
            </h2>
            <h3 style="color: #dc3232; margin-bottom: 1.5em"><?= Sanitize::h($this->readMessage($this->e)) ?></h3>

            <h4>at Line (<?= $this->e->getLine() ?>)</h4>
            <p>on <?= $this->e->getFile() ?></p>
            <hr style="margin: 1.5em 0" />
            <h4>Stacktrace:</h4>
            <ul style="white-space: pre-line;">
                <li><?= str_replace("\n", "</li><li>", Sanitize::h($this->e->getTraceAsString())) ?></li>
            </ul>

            <?php if ($this->e instanceof DatabaseException) : ?>
                <hr style="margin: 1.5em 0" />
                <h4>Last Query:</h4>
                <p style="white-space: pre-line;"><?= SqlFormatter::format($this->e->getLastQuery()) ?></p>
            <?php endif; ?>
        </div>

        <?php
        return ob_get_clean();
    }

    private function readMessage(Throwable $e) {
        if ($e instanceof DatabaseException) {
            return 'Database Query Error: ' . $e->getMessage();
        }

        return $e->getMessage();
    }
}
