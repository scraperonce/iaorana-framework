<?php declare(strict_types=1);

namespace Iaorana\Framework\Views\Helpers;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Iaorana\Framework\Validators\ValidatableCollection;
use Iaorana\Framework\Presentations\Renderable;
use Iaorana\Framework\Facades\Sanitize;

class Flash implements Renderable {
    /**
     * @var ValidatableCollection
     */
    private $params;

    /**
     * @var Collection<string>
     */
    private $successes;

    /**
     * @var Collection<string>
     */
    private $infos;

    /**
     * @var Collection<string>
     */
    private $errors;

    /**
     * @var array
     */
    private $options = [
        'ok_label' => '0K: ',
        'info_label' => '',
        'error_label' => 'ERROR: '
    ];

    /**
     * @param array<ValidatableCollection> $params
     */
    public function __construct(ValidatableCollection $params, array $options = []) {
        $this->params = $params;
        $this->successes = new ArrayCollection([]);
        $this->infos = new ArrayCollection([]);
        $this->errors = new ArrayCollection([]);
        $this->options = array_merge($this->options, $options);
    }

    public function success(string $message) {
        $this->successes->add($message);
    }

    public function info(string $message) {
        $this->infos->add($message);
    }

    public function error(string $message) {
        $this->errors->add($message);
    }

    public function render(): string {
        $errors = $this->gatherAllErrors();
        $output = [];

        if (!$errors->isEmpty()) {
            foreach ($errors as $err) {
                $output[] = '<div class="notice notice-error is-dismissible">';
                $output[] = '<p><strong>' . Sanitize::h($this->options['error_label']) . '</strong>';
                $output[] = '<span>' . Sanitize::h($err) . '</span></p>';
                $output[] = '</div>';
            }
        }

        if (!$this->infos->isEmpty()) {
            foreach ($this->infos as $info) {
                $output[] = '<div class="notice notice-info is-dismissible">';
                $output[] = '<p><strong>' . Sanitize::h($this->options['info_label']) . '</strong>';
                $output[] = '<span>' . Sanitize::h($info) . '</span></p>';
                $output[] = '</div>';
            }
        }

        if (!$this->successes->isEmpty()) {
            foreach ($this->successes as $success) {
                $output[] = '<div class="notice notice-success is-dismissible">';
                $output[] = '<p><strong>' . Sanitize::h($this->options['ok_label']) . '</strong>';
                $output[] = '<span>' . Sanitize::h($success) . '</span></p>';
                $output[] = '</div>';
            }
        }

        if (count($output) > 0) {
            $output = array_merge(
                [ '<div class="notice-wrapper">' ],
                $output,
                [ '</div>' ],
            );
        }

        return implode("\n", $output);
    }

    private function gatherAllErrors(): Collection {
        return new ArrayCollection(array_merge(
            $this->params->getErrors()->toArray(),
            $this->errors->toArray()
        ));
    }
}
