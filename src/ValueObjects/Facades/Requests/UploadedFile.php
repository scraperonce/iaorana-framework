<?php

namespace Iaorana\Framework\ValueObjects\Facades\Requests;

use Iaorana\Framework\ValueObjects\MimeTypedFileValueObject;

class UploadedFile implements MimeTypedFileValueObject {
    const UPLOAD_ERR_OK = UPLOAD_ERR_OK;
    const UPLOAD_ERR_INI_SIZE = UPLOAD_ERR_INI_SIZE;
    const UPLOAD_ERR_FORM_SIZE = UPLOAD_ERR_FORM_SIZE;
    const UPLOAD_ERR_PARTIAL = UPLOAD_ERR_PARTIAL;
    const UPLOAD_ERR_NO_FILE = UPLOAD_ERR_NO_FILE;
    const UPLOAD_ERR_NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;
    const UPLOAD_ERR_CANT_WRITE = UPLOAD_ERR_CANT_WRITE;
    const UPLOAD_ERR_EXTENSION = UPLOAD_ERR_EXTENSION;
    const UPLOAD_ERR_NO_TMP_FILE = 100;
    const UPLOAD_ERR_UNKNOWN = 200;

    private $tmp_file_path;

    private $file_original_name;

    private $file_size;

    private $mime_type;

    private $error_code;

    public static $messages = [
        self::UPLOAD_ERR_OK => 'There is no error, the file uploaded with success',
        self::UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
        self::UPLOAD_ERR_FORM_SIZE
            => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
        self::UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
        self::UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        self::UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
        self::UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        self::UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
        self::UPLOAD_ERR_NO_TMP_FILE => 'Missing a temporary file',
        self::UPLOAD_ERR_UNKNOWN => 'A unknown error occurred at file upload',
    ];

    /**
     * @throws \InvalidArgumentException
     */
    public static function fromFileRequest(array $global_file_item): MimeTypedFileValueObject {
        if (self::exists($global_file_item) === false) {
            throw new \InvalidArgumentException(static::$messages[self::UPLOAD_ERR_NO_FILE], self::UPLOAD_ERR_NO_FILE);
        }

        $file = new self();
        $file->tmp_file_path = $global_file_item['tmp_name'];
        $file->file_original_name = $global_file_item['name'];
        $file->file_size = $global_file_item['size'];
        $file->mime_type = $global_file_item['type'];

        $error = $global_file_item['error'];

        $file->error_code = empty(static::$messages[$error]) ? self::UPLOAD_ERR_UNKNOWN : $error;

        if ($file->error_code === self::UPLOAD_ERR_UNKNOWN || $file->error_code === self::UPLOAD_ERR_OK) {
            if (is_uploaded_file($file->tmp_file_path) === false) {
                $file->error_code = self::UPLOAD_ERR_NO_TMP_FILE;
            }
        }

        return $file;
    }

    public static function exists(array $global_file_item): bool {
        return !empty($global_file_item);
    }

    public function fileSize(): int {
        return $this->file_size;
    }

    public function fileMimeType(): string {
        return mime_content_type($this->tmp_file_path);
    }

    public function fileOriginalName(): string {
        return $this->file_original_name;
    }

    public function fileName(): string {
        return basename($this->tmp_file_path);
    }

    public function filePath(): string {
        return $this->tmp_file_path;
    }

    public function errorCode(): int {
        return $this->error_code;
    }

    public function errorMessage(): string {
        return static::$messages[ $this->error_code ];
    }

    public function valueOf(): string {
        return file_get_contents($this->tmp_file_path);
    }

    public function output(): void {
        echo $this->valueOf();
    }
}
