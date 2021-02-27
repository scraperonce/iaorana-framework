<?php declare(strict_types=1);

namespace Iaorana\Framework\Components\Parameters;

use Iaorana\Framework\Facades\Parameters\Parameter as ParametersParameter;

class Parameter extends ParametersParameter {
    public function validate(): bool {
        $this->checkNonce();

        return $this->hasError() === false;
    }

    private function checkNonce(): void {
        if (static::nonceKey()) {
            check_admin_referer(static::FORM_NONCE_KEY, static::REQUEST_NONCE_FIELD_NAME);
        }
    }
}
