<?php declare(strict_types=1);

namespace Iaorana\Framework\Frontend;

use \Iaorana\Framework\Facades\Parameters;

class Parameter extends Parameters\Parameter {

    const FORM_NONCE_KEY = null;
    const REQUEST_NONCE_FIELD_NAME = '_wpnonce_frontend';

    protected $errors = [];

    public function validate(): bool {
        $this->validateNonce();

        return $this->hasError() === false;
    }

    private function validateNonce(): void {
        if (static::FORM_NONCE_KEY) {
            $nonce = $this->input(static::REQUEST_NONCE_FIELD_NAME);
            if (wp_verify_nonce($nonce, static::FORM_NONCE_KEY)) {
                $this->addError('The request is expired');
            }
        }
    }
}
