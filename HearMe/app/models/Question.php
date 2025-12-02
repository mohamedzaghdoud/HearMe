<?php
class Question {

    // Private attributes (encapsulation)
    private $id_question;
    private $id_quiz;
    private $texte_question;
    private $ordre;

    private $option1_texte;
    private $option1_score;
    private $option2_texte;
    private $option2_score;
    private $option3_texte;
    private $option3_score;
    private $option4_texte;
    private $option4_score;

    private $date_creation;

    // Constructor
    public function __construct($data = null) {
        if ($data) {
            $this->setIdQuestion($data['id_question'] ?? null);
            $this->setIdQuiz($data['id_quiz'] ?? null);
            $this->setTexteQuestion($data['texte_question'] ?? '');
            $this->setOrdre($data['ordre'] ?? 1);

            $this->setOption1Texte($data['option1_texte'] ?? '');
            $this->setOption1Score($data['option1_score'] ?? 1);

            $this->setOption2Texte($data['option2_texte'] ?? '');
            $this->setOption2Score($data['option2_score'] ?? 2);

            $this->setOption3Texte($data['option3_texte'] ?? '');
            $this->setOption3Score($data['option3_score'] ?? 3);

            $this->setOption4Texte($data['option4_texte'] ?? null);
            $this->setOption4Score($data['option4_score'] ?? null);

            $this->setDateCreation($data['date_creation'] ?? null);
        }
    }

    // --- GETTERS ---
    public function getIdQuestion() { return $this->id_question; }
    public function getIdQuiz() { return $this->id_quiz; }
    public function getTexteQuestion() { return $this->texte_question; }
    public function getOrdre() { return $this->ordre; }
    public function getOption1Texte() { return $this->option1_texte; }
    public function getOption1Score() { return $this->option1_score; }
    public function getOption2Texte() { return $this->option2_texte; }
    public function getOption2Score() { return $this->option2_score; }
    public function getOption3Texte() { return $this->option3_texte; }
    public function getOption3Score() { return $this->option3_score; }
    public function getOption4Texte() { return $this->option4_texte; }
    public function getOption4Score() { return $this->option4_score; }
    public function getDateCreation() { return $this->date_creation; }

    // --- SETTERS ---
    public function setIdQuestion($id) { $this->id_question = $id; }
    public function setIdQuiz($id) { $this->id_quiz = $id; }

    public function setTexteQuestion($texte) {
        $this->texte_question = trim($texte);
    }

    public function setOrdre($ordre) {
        $this->ordre = max(1, (int)$ordre); // validation: minimum 1
    }

    public function setOption1Texte($v) { $this->option1_texte = $v; }
    public function setOption1Score($v) { $this->option1_score = (int)$v; }

    public function setOption2Texte($v) { $this->option2_texte = $v; }
    public function setOption2Score($v) { $this->option2_score = (int)$v; }

    public function setOption3Texte($v) { $this->option3_texte = $v; }
    public function setOption3Score($v) { $this->option3_score = (int)$v; }

    public function setOption4Texte($v) { $this->option4_texte = $v; }
    public function setOption4Score($v) { $this->option4_score = $v !== null ? (int)$v : null; }

    public function setDateCreation($date) { $this->date_creation = $date; }

    // Same getOptions() method, nothing changes
    public function getOptions() {
        $options = [
            ['texte' => $this->option1_texte, 'score' => $this->option1_score],
            ['texte' => $this->option2_texte, 'score' => $this->option2_score],
            ['texte' => $this->option3_texte, 'score' => $this->option3_score],
        ];

        if ($this->option4_texte) {
            $options[] = ['texte' => $this->option4_texte, 'score' => $this->option4_score];
        }

        return $options;
    }

    // Return array
    public function toArray() {
        return [
            'id_question' => $this->id_question,
            'id_quiz' => $this->id_quiz,
            'texte_question' => $this->texte_question,
            'ordre' => $this->ordre,
            'option1_texte' => $this->option1_texte,
            'option1_score' => $this->option1_score,
            'option2_texte' => $this->option2_texte,
            'option2_score' => $this->option2_score,
            'option3_texte' => $this->option3_texte,
            'option3_score' => $this->option3_score,
            'option4_texte' => $this->option4_texte,
            'option4_score' => $this->option4_score,
            'date_creation' => $this->date_creation
        ];
    }
}
?>
