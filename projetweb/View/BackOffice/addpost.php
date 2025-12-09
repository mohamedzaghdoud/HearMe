<?php
// Inclusion du contrôleur pour la gestion de l'ajout
include '../../controller/PostController.php';
// Inclusion du modèle (classe Post)
require_once __DIR__ . '/../../Model/Post.php';

$error = "";
$postC = new PostController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $content = $_POST['content'] ?? "";
    $imageName = null;

    if (!empty($content)) {

        // Gestion upload image
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = '../../uploads/';
            // Création d'un nom de fichier unique pour éviter les conflits
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            $uploadPath = $uploadDir . $imageName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $error = "Erreur lors de l'upload de l'image.";
            }
        }

        if (empty($error)) {
            // Création de l'objet Post
            // ID = null (sera auto-généré par la base de données)
            // Likes = 0 (valeur par défaut)
            // created_at = nouvelle date/heure
            $post = new Post(
                null,
                $content,
                $imageName,
                0,
                new DateTime()
            );

            // Appel de la méthode d'ajout du contrôleur
            $postC->addpost($post);
            // Redirection vers la liste des posts après l'ajout réussi
            header("Location: postList.php");
            exit;
        }
    } else {
        $error = "Le contenu est obligatoire.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un Post</title>

    <style>
        .error {
            color: red;
            font-size: 14px;
            margin-top: 3px;
        }
    </style>

    <script>
        function validateForm() {
            let valid = true;

            // Reset des messages d'erreur
            document.getElementById("errorContent").innerText = "";
            document.getElementById("errorImage").innerText = "";

            let content = document.getElementById("content").value.trim();
            let image = document.getElementById("image").value;

            // Vérification côté client: contenu obligatoire
            if (content === "") {
                document.getElementById("errorContent").innerText = "Le contenu est obligatoire.";
                valid = false;
            }

            // Vérification du format de l'image si un fichier est sélectionné
            if (image !== "") {
                let ext = image.split('.').pop().toLowerCase();
                let allowed = ["jpg", "jpeg", "png", "gif"];

                if (!allowed.includes(ext)) {
                    document.getElementById("errorImage").innerText =
                        "Image invalide. Formats autorisés : jpg, jpeg, png, gif.";
                    valid = false;
                }
            }

            return valid;
        }
    </script>

</head>

<body>

    <h1>Ajouter un Post</h1>

    <?php if (!empty($error)): ?>
        <p style="color: red;"><?= $error ?></p>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" novalidate onsubmit="return validateForm();">

        <label for="content">Contenu :</label><br>
        <textarea id="content" name="content" rows="4" cols="50"></textarea>
        <div id="errorContent" class="error"></div>
        <br><br>

        <label for="image">Image (optionnelle) :</label><br>
        <input type="file" id="image" name="image">
        <div id="errorImage" class="error"></div>
        <br><br>

        <button type="submit">Publier</button>
    </form>
    
    <p><a href="postList.php">Retour à la liste des posts</a></p>

</body>

</html>