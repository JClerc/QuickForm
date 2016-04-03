<?php

namespace Model\Service;
use Model\Base\Service;

/**
 * Mail service
 *
 * @uses PHPMailer
 * @throws InternalException
 */
class Mail extends Service {

    const DEFAULT_NAME     = 'QForm';
    const DEFAULT_USERNAME = 'qform.service@gmail.com';
    const DEFAULT_PASSWORD = '';

    public function send($subject, $recipient, $html, $data = null) {
        $mail = $this->build();
        $mail->Subject = $subject;
        if (isset($data)) {
            if (!is_array($data)) $data = [];
            $mail->MsgHTML($this->loadTemplate($html, $data));
        } else {
            $mail->MsgHTML($html);
        }
        $mail->AddAddress($recipient);
        if (!$mail->Send()) {
            throw new \InternalException($mail->ErrorInfo);
        }
        return true;
    }

    public function build() {
        $mail = $this->new();
        if (!empty(self::DEFAULT_PASSWORD)) {
            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = 'ssl';
            $mail->Host       = 'smtp.gmail.com';
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';
            $mail->Username   = self::DEFAULT_USERNAME;
            $mail->Password   = self::DEFAULT_PASSWORD;
            $mail->SetFrom(self::DEFAULT_USERNAME, self::DEFAULT_NAME);
            $mail->AddReplyTo(self::DEFAULT_USERNAME, self::DEFAULT_NAME);
        } else {
            $mail->SetFrom($this->getServerMail(), self::DEFAULT_NAME);
            $mail->AddReplyTo($this->getServerMail(), self::DEFAULT_NAME);
        }
        return $mail;
    }

    public function loadTemplate($path, array $data = []) {
        $path = TEMPLATE . 'mail/' . $path . '.php';
        if (is_file($path)) {
            extract($data, EXTR_SKIP);
            ob_start();
            require $path;
            return ob_get_clean();
        } else {
            throw new \InternalException('Mail template ' . $path . ' doesn\'t exists');
        }
    }

    public function new($default = true) {
        return new \PHPMailer;
    }

    private function getServerMail() {
        if ($host = $_SERVER['HTTP_X_FORWARDED_HOST']) {
            $elements = explode(',', $host);
            $host = trim(end($elements));
        } else {
            if (!$host = $_SERVER['HTTP_HOST']) {
                if (!$host = $_SERVER['SERVER_NAME']) {
                    $host = !empty($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '';
                }
            }
        }

        // Remove port number from host
        $host = preg_replace('/:\d+$/', '', $host);

        if (empty($host))
            throw new \InternalException('Cannot get the server email');

        return 'noreply@' . trim($host);
    }

}
