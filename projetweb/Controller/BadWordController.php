<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/BadWord.php');

class BadWordController {

    // LIST ALL BAD WORDS
    public function badWordList() {
        $sql = "SELECT * FROM bad_words ORDER BY word ASC";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // ADD BAD WORD
    public function addBadWord(BadWord $badWord) {
        $sql = "INSERT INTO bad_words (word) VALUES (:word)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'word' => strtolower(trim($badWord->getWord()))
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    // DELETE BAD WORD BY ID
    public function deleteBadWord($id) {
        $sql = "DELETE FROM bad_words WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // GET ALL BAD WORDS AS ARRAY
    public function getAllBadWords() {
        $sql = "SELECT word FROM bad_words";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            $words = $query->fetchAll(PDO::FETCH_COLUMN);
            return $words;
        } catch (Exception $e) {
            return [];
        }
    }

    // FILTER TEXT - Replace bad words with stars
    public function filterText($text) {
        $badWords = $this->getAllBadWords();
        $filteredText = $text;
        
        foreach ($badWords as $badWord) {
            // Case-insensitive replacement
            $pattern = '/\b' . preg_quote($badWord, '/') . '\b/i';
            $stars = str_repeat('*', mb_strlen($badWord));
            $filteredText = preg_replace($pattern, $stars, $filteredText);
        }
        
        return $filteredText;
    }
}
?>

