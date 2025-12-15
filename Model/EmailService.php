<?php
/**
 * Service d'envoi d'emails SIMPLE - HearMe
 * Version SANS PHPMailer - Utilise mail() PHP
 * Emplacement : MON PROJET/Model/EmailService.php
 */

class EmailService {
    private $from;
    private $fromName = 'HearMe Platform';
    private $baseUrl;
    
    // 🔧 CONFIGURATION SMTP pour Windows/XAMPP
    private $smtpHost = 'smtp.gmail.com';
    private $smtpPort = 587;
    private $smtpUsername;
    private $smtpPassword;

    public function __construct() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $this->baseUrl = $protocol . '://' . $host . '/hearme';
        
        // 🔧 CONFIGUREZ VOS IDENTIFIANTS ICI
        $this->smtpUsername = 'malekkassous3@gmail.com'; // ⚠️ À MODIFIER
        $this->smtpPassword = 'hubf jgte mrtr oefu'; // ⚠️ À MODIFIER
        $this->from = $this->smtpUsername;
        
        // Configurer php.ini pour SMTP (pour Windows/XAMPP)
        $this->configurePhpSMTP();
    }

    /**
     * Configure SMTP pour mail() PHP sur Windows
     */
    private function configurePhpSMTP() {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Configuration pour Windows uniquement
            ini_set('SMTP', $this->smtpHost);
            ini_set('smtp_port', $this->smtpPort);
            ini_set('sendmail_from', $this->from);
        }
    }

    /**
     * Génère un token sécurisé
     */
    public static function generateToken() {
        return bin2hex(random_bytes(32));
    }

    /**
     * Envoie un email de vérification
     */
    public function sendVerificationEmail($email, $token) {
        $verifyLink = $this->baseUrl . '/View/FrontOffice/verify-email.php?token=' . $token;
        
        $subject = 'Vérifiez votre email - HearMe';
        
        $htmlMessage = $this->getEmailTemplate(
            'Bienvenue sur HearMe !',
            'Merci de vous être inscrit. Pour activer votre compte, veuillez cliquer sur le bouton ci-dessous :',
            'Vérifier mon email',
            $verifyLink,
            'Ce lien expirera dans 24 heures.'
        );

        return $this->sendEmail($email, $subject, $htmlMessage);
    }

    /**
     * Envoie un email de réinitialisation de mot de passe
     */
    public function sendPasswordResetEmail($email, $token) {
        $resetLink = $this->baseUrl . '/View/FrontOffice/reset-password.php?token=' . $token;
        
        $subject = 'Réinitialisation de votre mot de passe - HearMe';
        
        $htmlMessage = $this->getEmailTemplate(
            'Réinitialisation de mot de passe',
            'Vous avez demandé à réinitialiser votre mot de passe. Cliquez sur le bouton ci-dessous pour continuer :',
            'Réinitialiser mon mot de passe',
            $resetLink,
            'Ce lien expirera dans 1 heure. Si vous n\'avez pas fait cette demande, ignorez cet email.'
        );

        return $this->sendEmail($email, $subject, $htmlMessage);
    }

    /**
     * Template HTML pour les emails
     */
    private function getEmailTemplate($title, $message, $buttonText, $buttonLink, $footer) {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
            <style>
                body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
                .header { background: linear-gradient(135deg, #5BA8C8, #7AC5E0); padding: 30px; text-align: center; color: white; }
                .header h1 { margin: 0; font-size: 28px; }
                .content { padding: 40px 30px; text-align: center; }
                .content p { color: #555; line-height: 1.6; font-size: 16px; margin-bottom: 30px; }
                .button { display: inline-block; padding: 16px 32px; background: linear-gradient(135deg, #5BA8C8, #7AC5E0); color: white; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; }
                .footer { padding: 20px 30px; background: #f9f9f9; text-align: center; color: #999; font-size: 13px; border-top: 1px solid #eee; }
                .link { color: #5BA8C8; word-break: break-all; font-size: 12px; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>HearMe</h1>
                </div>
                <div class='content'>
                    <h2 style='color: #5BA8C8; margin-bottom: 20px;'>{$title}</h2>
                    <p>{$message}</p>
                    <a href='{$buttonLink}' class='button'>{$buttonText}</a>
                    <div class='link'>
                        <p>Ou copiez ce lien dans votre navigateur :</p>
                        <p>{$buttonLink}</p>
                    </div>
                </div>
                <div class='footer'>
                    <p>{$footer}</p>
                    <p>&copy; " . date('Y') . " HearMe - Tous droits réservés</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * 🚀 Fonction d'envoi d'email
     */
    private function sendEmail($to, $subject, $htmlMessage) {
        // Toujours logger en local
        if ($this->isLocalEnvironment()) {
            $this->logEmailToFile($to, $subject, $htmlMessage);
        }

        // Essayer d'envoyer avec SMTP si configuré
        if ($this->canUseSMTP()) {
            return $this->sendWithSMTPSocket($to, $subject, $htmlMessage);
        }

        // Sinon utiliser mail() PHP standard
        return $this->sendWithPHPMail($to, $subject, $htmlMessage);
    }

    /**
     * Vérifie si SMTP est configuré
     */
    private function canUseSMTP() {
        return !empty($this->smtpUsername) && 
               !empty($this->smtpPassword) &&
               $this->smtpUsername !== 'votre.email@gmail.com';
    }

    /**
     * 🚀 NOUVEAU : Envoi via socket SMTP (sans PHPMailer)
     */
    private function sendWithSMTPSocket($to, $subject, $htmlMessage) {
        try {
            // Connexion au serveur SMTP
            $socket = fsockopen(
                'ssl://' . $this->smtpHost,
                465, // Port SSL
                $errno,
                $errstr,
                30
            );

            if (!$socket) {
                error_log("❌ Erreur connexion SMTP: $errstr ($errno)");
                return $this->sendWithPHPMail($to, $subject, $htmlMessage);
            }

            // Lire la réponse du serveur
            $this->readSMTPResponse($socket);

            // EHLO
            fwrite($socket, "EHLO " . $_SERVER['HTTP_HOST'] . "\r\n");
            $this->readSMTPResponse($socket);

            // AUTH LOGIN
            fwrite($socket, "AUTH LOGIN\r\n");
            $this->readSMTPResponse($socket);

            // Username (base64)
            fwrite($socket, base64_encode($this->smtpUsername) . "\r\n");
            $this->readSMTPResponse($socket);

            // Password (base64)
            fwrite($socket, base64_encode($this->smtpPassword) . "\r\n");
            $response = $this->readSMTPResponse($socket);

            if (strpos($response, '235') === false) {
                error_log("❌ Authentification SMTP échouée");
                fclose($socket);
                return $this->sendWithPHPMail($to, $subject, $htmlMessage);
            }

            // MAIL FROM
            fwrite($socket, "MAIL FROM: <{$this->from}>\r\n");
            $this->readSMTPResponse($socket);

            // RCPT TO
            fwrite($socket, "RCPT TO: <{$to}>\r\n");
            $this->readSMTPResponse($socket);

            // DATA
            fwrite($socket, "DATA\r\n");
            $this->readSMTPResponse($socket);

            // En-têtes et contenu
            $headers = "From: {$this->fromName} <{$this->from}>\r\n";
            $headers .= "To: <{$to}>\r\n";
            $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: 8bit\r\n";
            $headers .= "\r\n";

            fwrite($socket, $headers . $htmlMessage . "\r\n.\r\n");
            $this->readSMTPResponse($socket);

            // QUIT
            fwrite($socket, "QUIT\r\n");
            fclose($socket);

            error_log("✅ Email envoyé via SMTP socket à $to");
            return true;

        } catch (Exception $e) {
            error_log("❌ Erreur SMTP socket: " . $e->getMessage());
            return $this->sendWithPHPMail($to, $subject, $htmlMessage);
        }
    }

    /**
     * Lit la réponse du serveur SMTP
     */
    private function readSMTPResponse($socket) {
        $response = '';
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') break;
        }
        return $response;
    }

    /**
     * Détecte si on est en environnement local
     */
    private function isLocalEnvironment() {
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return strpos($host, 'localhost') !== false || 
               strpos($host, '127.0.0.1') !== false ||
               strpos($host, '::1') !== false;
    }

    /**
     * Log les emails en développement
     */
    private function logEmailToFile($to, $subject, $htmlMessage) {
        $logDir = __DIR__ . '/../logs';
        
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $logFile = $logDir . '/emails_' . date('Y-m-d') . '.log';
        
        $logContent = "\n\n" . str_repeat('=', 80) . "\n";
        $logContent .= "📧 EMAIL ENVOYÉ\n";
        $logContent .= str_repeat('=', 80) . "\n";
        $logContent .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $logContent .= "À: $to\n";
        $logContent .= "Sujet: $subject\n";
        $logContent .= str_repeat('-', 80) . "\n";
        
        // Extraire le lien du message
        if (preg_match('/href=["\']([^"\']+)["\']/', $htmlMessage, $matches)) {
            $logContent .= "🔗 LIEN:\n";
            $logContent .= $matches[1] . "\n";
            $logContent .= str_repeat('-', 80) . "\n";
        }
        
        $textMessage = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlMessage));
        $textMessage = html_entity_decode($textMessage, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $textMessage = preg_replace('/\n{3,}/', "\n\n", $textMessage);
        
        $logContent .= "📄 MESSAGE:\n";
        $logContent .= trim($textMessage) . "\n";
        $logContent .= str_repeat('=', 80) . "\n";

        file_put_contents($logFile, $logContent, FILE_APPEND);
        
        return true;
    }

    /**
     * Envoi avec mail() PHP standard
     */
    private function sendWithPHPMail($to, $subject, $htmlMessage) {
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $this->fromName . ' <' . $this->from . '>',
            'Reply-To: ' . $this->from,
            'X-Mailer: PHP/' . phpversion()
        ];

        // Encoder le sujet en UTF-8
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $result = @mail($to, $encodedSubject, $htmlMessage, implode("\r\n", $headers));
        
        if ($result) {
            error_log("✅ Email envoyé avec mail() à $to");
        } else {
            error_log("❌ Échec envoi email avec mail() à $to");
            error_log("⚠️ Vérifiez la configuration SMTP dans php.ini");
        }
        
        return $result;
    }
}
?>