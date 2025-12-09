<?php
class Comment {
    private ?int $id;
    private ?int $post_id;
    private ?string $text;
    private ?DateTime $created_at;

    // Constructor
    public function __construct(?int $id, ?int $post_id, ?string $text, ?DateTime $created_at) {
        $this->id = $id;
        $this->post_id = $post_id;
        $this->text = $text;
        $this->created_at = $created_at;
    }

    // Getters and Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getPost_id(): ?int {
        return $this->post_id;
    }

    public function setPost_id(?int $post_id): void {
        $this->post_id = $post_id;
    }

    public function getText(): ?string {
        return $this->text;
    }

    public function setText(?string $text): void {
        $this->text = $text;
    }

    public function getCreated_at(): ?DateTime {
        return $this->created_at;
    }

    public function setCreated_at(?DateTime $created_at): void {
        $this->created_at = $created_at;
    }
}
?>