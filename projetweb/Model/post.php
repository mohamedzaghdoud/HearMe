<?php
class post {
    private ?int $id;
    private ?string $content;
    private ?string $image;
    private ?int $likes;
    private ?DateTime $created_at;
    

    //Constructor
    public function __construct(?int $id, ?string $content, ?string $image, ?int $likes,?DateTime $created_at) {
        $this->id = $id;
        $this->content = $content;
        $this->image = $image;
        $this->likes= $likes;
        $this->created_at = $created_at;
       
    }

    public function show() {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>content</th><th>image</th><th>likes</th><th>created_at</th></tr>";
        echo "<tr>";
        echo "<td>{$this->id}</td>";
        echo "<td>{$this->content}</td>";
        echo "<td>{$this->image}</td>";
        echo "<td>{$this->likes}</td>";
        echo "<td>" . ($this->created_at ? $this->created_at->format('Y-m-d') : '') . "</td>";
        echo "</tr>";
        echo "</table>";
    }

    // Getters and Setters
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function getContent(): ?string {
        return $this->content;
    }

    public function setContent(?string $content): void {
        $this->content = $content;
    }

    public function getImage(): ?string {
        return $this->image;
    }

    public function setImage(?string $image): void {
        $this->image = $image;
    }

    public function getLikes(): ?int {
        return $this->likes;
    }

    public function setLikes(?int $likes): void {
        $this->likes = $likes;
    }


    public function getCreated_at(): ?DateTime {
        return $this->created_at;
    }

    public function setCreated_at(?DateTime $created_at): void {
        $this->created_at = $created_at;
    }



}
?>