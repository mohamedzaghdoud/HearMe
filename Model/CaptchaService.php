<?php
/**
 * Service CAPTCHA SIMPLIFIÉ - HearMe
 * Emplacement : MON PROJET/Model/CaptchaService.php
 */

class CaptchaService {
    
    /**
     * ✅ CORRECTION : Génère un CAPTCHA de type Emoji Math SIMPLIFIÉ
     */
    public static function generateEmojiMath() {
        secureSession();
        
        $emojis = [
            1 => '🍎', 2 => '🍌', 3 => '🍊', 
            4 => '🍇', 5 => '🍓'
        ];
        
        $num1 = rand(1, 5);
        $num2 = rand(1, 5);
        $operation = rand(0, 1);
        
        if ($operation === 0) {
            // Addition
            $question = "{$emojis[$num1]} + {$emojis[$num2]} = ?";
            $answer = $num1 + $num2;
        } else {
            // Soustraction
            if ($num1 < $num2) {
                $temp = $num1;
                $num1 = $num2;
                $num2 = $temp;
            }
            $question = "{$emojis[$num1]} - {$emojis[$num2]} = ?";
            $answer = $num1 - $num2;
        }
        
        // Créer la légende
        $legend = [];
        $usedNumbers = array_unique([$num1, $num2]);
        foreach ($usedNumbers as $n) {
            if ($n >= 1 && $n <= 5) {
                $legend[$emojis[$n]] = $n;
            }
        }
        
        // ✅ CORRECTION : Sauvegarder en session
        $_SESSION['captcha_answer'] = $answer;
        $_SESSION['captcha_time'] = time();
        $_SESSION['captcha_type'] = 'emoji';
        
        return [
            'type' => 'emoji',
            'question' => $question,
            'legend' => $legend,
            'answer' => $answer
        ];
    }
    
    /**
     * ✅ CORRECTION : Vérifie la réponse CAPTCHA SIMPLIFIÉ
     */
    public static function verifyCaptcha($userAnswer) {
        secureSession();
        
        if (!isset($_SESSION['captcha_answer'])) {
            error_log("CAPTCHA ERROR: Pas de réponse en session");
            return false;
        }
        
        // Vérifier l'expiration (5 minutes)
        if (isset($_SESSION['captcha_time']) && (time() - $_SESSION['captcha_time']) > 300) {
            error_log("CAPTCHA ERROR: Expiré");
            self::clearCaptcha();
            return false;
        }
        
        $correctAnswer = intval($_SESSION['captcha_answer']);
        $userAnswerInt = intval($userAnswer);
        $isValid = ($userAnswerInt === $correctAnswer);
        
        error_log("CAPTCHA RESULT: Attendu=$correctAnswer, Reçu=$userAnswerInt, Valide=" . ($isValid ? "OUI" : "NON"));
        
        // Nettoyer après vérification
        self::clearCaptcha();
        
        return $isValid;
    }
    
    /**
     * Nettoie les données CAPTCHA
     */
    public static function clearCaptcha() {
        secureSession();
        unset($_SESSION['captcha_answer']);
        unset($_SESSION['captcha_time']);
        unset($_SESSION['captcha_type']);
    }
}
?>