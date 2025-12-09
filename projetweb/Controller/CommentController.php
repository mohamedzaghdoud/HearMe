<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../Model/Comment.php');
require_once(__DIR__ . '/BadWordController.php');

class CommentController {

    // LIST ALL COMMENTS FOR A SPECIFIC POST
    public function getCommentsByPost($post_id) {
        $sql = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY created_at DESC";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':post_id', $post_id);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // COUNT COMMENTS FOR A POST
    public function countCommentsByPost($post_id) {
        $sql = "SELECT COUNT(*) as total FROM comments WHERE post_id = :post_id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':post_id', $post_id);
            $query->execute();
            $result = $query->fetch();
            return $result['total'];
        } catch (Exception $e) {
            return 0;
        }
    }

    // ADD COMMENT
    public function addComment(Comment $comment) {
        // Filtrer le texte avant insertion
        $badWordController = new BadWordController();
        $filteredText = $badWordController->filterText($comment->getText());
        $comment->setText($filteredText);
        
        $sql = "INSERT INTO comments (post_id, text, created_at)
                VALUES (:post_id, :text, :created_at)";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'post_id' => $comment->getPost_id(),
                'text' => $comment->getText(),
                'created_at' => $comment->getCreated_at() ? $comment->getCreated_at()->format('Y-m-d H:i:s') : null
            ]);
            return true;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
    }

    // UPDATE COMMENT
    public function updateComment(Comment $comment, $id) {
        // Filtrer le texte avant mise à jour
        $badWordController = new BadWordController();
        $filteredText = $badWordController->filterText($comment->getText());
        $comment->setText($filteredText);
        
        $sql = "UPDATE comments SET 
                   text = :text
                WHERE id = :id";

        try {
            $db = config::getConnexion();
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $id,
                'text' => $comment->getText()
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // DELETE COMMENT
    public function deleteComment($id) {
        $sql = "DELETE FROM comments WHERE id = :id";
        $db = config::getConnexion();
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);

        try {
            $req->execute();
            return true;
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // SHOW COMMENT BY ID
    public function showComment($id) {
        $sql = "SELECT * FROM comments WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);

        try {
            $query->execute();
            $commentData = $query->fetch();

            if (!$commentData) {
                return null;
            }

            return new Comment(
                $commentData['id'],
                $commentData['post_id'],
                $commentData['text'],
                new DateTime($commentData['created_at'])
            );
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // LIST ALL COMMENTS (for admin)
    public function commentList() {
        $sql = "SELECT * FROM comments ORDER BY created_at DESC";
        $db = config::getConnexion();
        try {
            $list = $db->query($sql);
            return $list;
        } catch (Exception $e) {
            die('Error:' . $e->getMessage());
        }
    }
}
?>