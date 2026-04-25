<?php

/**
 * Mailer — SMTP sender with PHPMailer, and a mail() fallback.
 * No business logic here (no subject lines, no templates).
 */
class Mailer
{
    private array $config;
    private array $debug = [];

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? require __DIR__ . '/../../config/mail.php';
    }

    /**
     * @param string $to
     * @param string $subject
     * @param string $htmlBody
     * @param array  $options  ['reply_to_email' => ?, 'reply_to_name' => ?, 'attachment_path' => ?, 'attachment_name' => ?]
     * @return array{success: bool, debug: array}
     */
    public function send(string $to, string $subject, string $htmlBody, array $options = []): array
    {
        $this->debug = [
            'to'        => $to,
            'from'      => $this->config['from_email'],
            'subject'   => $subject,
            'has_attachment' => !empty($options['attachment_path']) && file_exists($options['attachment_path']),
        ];

        $phpmailer = $this->loadPhpMailer();

        if ($phpmailer) {
            $sent = $this->sendViaPhpMailer($to, $subject, $htmlBody, $options);
            if ($sent) {
                return ['success' => true, 'debug' => $this->debug];
            }
        }

        // Fallback to native mail()
        return $this->sendViaMail($to, $subject, $htmlBody, $options);
    }

    private function loadPhpMailer(): bool
    {
        if (class_exists(\PHPMailer\PHPMailer\PHPMailer::class)) {
            return true;
        }
        $base = $this->config['phpmailer_path'] ?? '';
        if ($base && file_exists("$base/PHPMailer.php")) {
            require_once "$base/PHPMailer.php";
            require_once "$base/SMTP.php";
            require_once "$base/Exception.php";
            return true;
        }
        $this->debug['phpmailer'] = 'not found';
        return false;
    }

    private function sendViaPhpMailer(string $to, string $subject, string $htmlBody, array $options): bool
    {
        try {
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = $this->config['smtp_host'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $this->config['username'];
            $mail->Password   = $this->config['password'];
            $mail->SMTPSecure = $this->config['smtp_secure'];
            $mail->Port       = $this->config['smtp_port'];
            $mail->CharSet    = 'UTF-8';
            $mail->isHTML(true);

            $mail->setFrom($this->config['from_email'], $this->config['from_name']);
            $mail->addAddress($to);

            if (!empty($options['reply_to_email'])) {
                $mail->addReplyTo(
                    $options['reply_to_email'],
                    $options['reply_to_name'] ?? $options['reply_to_email']
                );
            }

            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;
            $mail->AltBody = strip_tags($htmlBody);

            if (!empty($options['attachment_path']) && file_exists($options['attachment_path'])) {
                $mail->addAttachment($options['attachment_path'], $options['attachment_name'] ?? '');
            }

            $mail->send();
            $this->debug['method'] = 'phpmailer';
            return true;
        } catch (\Throwable $e) {
            $this->debug['phpmailer_error'] = $e->getMessage();
            return false;
        }
    }

    private function sendViaMail(string $to, string $subject, string $htmlBody, array $options): array
    {
        $from     = $this->config['from_email'];
        $fromName = $this->config['from_name'];
        $hasAttachment = !empty($options['attachment_path']) && file_exists($options['attachment_path']);

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "From: $fromName <$from>\r\n";

        if (!empty($options['reply_to_email'])) {
            $name = $options['reply_to_name'] ?? $options['reply_to_email'];
            $headers .= "Reply-To: $name <{$options['reply_to_email']}>\r\n";
        }

        $subjectEncoded = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        if (!$hasAttachment) {
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();
            $ok = @mail($to, $subjectEncoded, $htmlBody, $headers, "-f$from");
            $this->debug['method'] = 'mail()';
            return ['success' => (bool)$ok, 'debug' => $this->debug];
        }

        $body = $this->buildMultipartBody(
            $htmlBody,
            $options['attachment_path'],
            $options['attachment_name'] ?? basename($options['attachment_path']),
            $boundary
        );
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();

        $ok = @mail($to, $subjectEncoded, $body, $headers, "-f$from");
        $this->debug['method'] = 'mail()+attachment';
        return ['success' => (bool)$ok, 'debug' => $this->debug];
    }

    private function buildMultipartBody(string $htmlBody, string $attachmentPath, string $attachmentName, ?string &$boundary): string
    {
        $boundary = 'mixed-' . md5(uniqid((string)time()));
        $boundaryAlt = 'alt-' . md5(uniqid((string)time()));

        $fileContent = chunk_split(base64_encode(file_get_contents($attachmentPath)));
        $ext = strtolower(pathinfo($attachmentName, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'txt' => 'text/plain', 'zip' => 'application/zip',
        ];
        $mime = $mimeTypes[$ext] ?? 'application/octet-stream';

        $body  = "--$boundary\r\n";
        $body .= "Content-Type: multipart/alternative; boundary=\"$boundaryAlt\"\r\n\r\n";

        $body .= "--$boundaryAlt\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= strip_tags($htmlBody) . "\r\n\r\n";

        $body .= "--$boundaryAlt\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $htmlBody . "\r\n\r\n";

        $body .= "--$boundaryAlt--\r\n\r\n";

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $mime; name=\"$attachmentName\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$attachmentName\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= $fileContent . "\r\n\r\n";
        $body .= "--$boundary--";

        return $body;
    }
}
