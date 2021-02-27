<?php declare(strict_types=1);

namespace Iaorana\Framework\Facades;

use Iaorana\Framework\ValueObjects\FileValueObject;

class Response {
    public static function bootstrap(): void {
        ob_start();
    }

    public static function redirect($url): void {
        wp_redirect($url);
        exit;
    }

    public static function download(FileValueObject $file): void {

        $filename = $file->fileOriginalName();

        //ホワイトスペース相当の文字をアンダースコアに
        $filename = preg_replace('/\\s/u', '_', $filename);
        //ファイル名に使えない文字をアンダースコアに
        $filename = str_replace(['\\', '/', ':', '*', '?', '"', '<', '>', '|'], '_', $filename);

        // Chunked Transferとして送信（そのためContent-Lengthをコメントアウト）
        header('Pragma: private');
        header('Cache-Control: private, must-revalidate');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($filename));
        // header('Content-Length: ' . $this->fileSize);
        header('Expires: Mon, 3 Jun 2005 10:00:00 GMT');
        header('Content-Transfer-Encoding: binary');

        self::expose($file);
        exit;
    }

    private static function expose(FileValueObject $file): void {
        // 現在のバッファリング内容をすべて削除
        while (ob_get_level()) {
            ob_end_clean();
        }

        // ダウンロード用バッファリング開始〜送信
        ob_start();
        print $file->output();
        flush();
        ob_flush();
        ob_end_clean();
    }
}
