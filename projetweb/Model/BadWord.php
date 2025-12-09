<?php
class BadWord {
    private ?int $id;
    private ?string $word;

    // Constructor
    public function __construct(?int $id, ?string $word) {
        $this->id = $id;
        $this->word = $word;
    }

    // Getters and Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getWord(): ?string {
        return $this->word;
    }

    public function setWord(?string $word): void {
        $this->word = $word;
    }
}
?>

