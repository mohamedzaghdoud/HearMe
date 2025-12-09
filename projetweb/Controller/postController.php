<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/Post.php');
require_once(__DIR__ . '/BadWordController.php');

class PostController {

    // LIST ALL POSTS
    public function postList() {
        $sql = "SELECT * FROM post ORDER BY created_at DESC";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // LIST POSTS BY DATE RANGE
    public function postListByDate($dateFrom, $dateTo) {
        $sql = "SELECT * FROM post WHERE DATE(created_at) BETWEEN :date_from AND :date_to ORDER BY created_at DESC";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':date_from', $dateFrom);
            $query->bindValue(':date_to', $dateTo);
            $query->execute();
            return $query;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // DELETE POST BY ID
    public function deletepost($id) {
        $sql = "DELETE FROM post WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }

    // ADD POST
    public function addpost(Post $post) {
        // Filtrer le contenu avant insertion
        $badWordController = new BadWordController();
        $filteredContent = $badWordController->filterText($post->getContent());
        $post->setContent($filteredContent);
        
        $sql = "INSERT INTO post (content, image, likes, created_at)
                VALUES (:content, :image, :likes, :created_at)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'content' => $post->getContent(),
                'image' => $post->getImage(),
                'likes' => $post->getLikes(),
                'created_at' => $post->getCreated_at() ? $post->getCreated_at()->format('Y-m-d H:i:s') : null
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    // UPDATE POST
    public function updatepost(Post $post, $id) {
        // Filtrer le contenu avant mise à jour
        $badWordController = new BadWordController();
        $filteredContent = $badWordController->filterText($post->getContent());
        $post->setContent($filteredContent);
        
        $sql = "UPDATE post SET 
                   content = :content,
                   image = :image,
                   likes = :likes,
                   created_at = :created_at
                WHERE id = :id";

        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'content' => $post->getContent(),
                'image' => $post->getImage(),
                'likes' => $post->getLikes(),
                'created_at' => $post->getCreated_at() ? $post->getCreated_at()->format('Y-m-d H:i:s') : null
            ]);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // SHOW POST BY ID
    public function showpost($id) {
        $sql = "SELECT * FROM post WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);

        try {
            $query->execute();
            $postData = $query->fetch();

            if (!$postData) {
                return null;
            }

            return new Post(
                $postData['id'],
                $postData['content'],
                $postData['image'],
                $postData['likes'],
                new DateTime($postData['created_at'])
            );
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }
}
?>