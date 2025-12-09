// ========================================
// Validation pour addPost.php
// ========================================
function validateAddPost() {
    let isValid = true;
    
    // Reset error messages
    document.getElementById('errorContent').textContent = '';
    document.getElementById('errorImage').textContent = '';
    document.getElementById('errorContent').classList.remove('show');
    document.getElementById('errorImage').classList.remove('show');
    
    // Validation du contenu
    const content = document.getElementById('content').value.trim();
    
    if (content === '') {
        document.getElementById('errorContent').textContent = 'Le contenu est obligatoire.';
        document.getElementById('errorContent').classList.add('show');
        document.getElementById('content').style.borderColor = '#d32f2f';
        isValid = false;
    } else if (content.length > 500) {
        document.getElementById('errorContent').textContent = 'Le contenu ne doit pas dépasser 500 caractères.';
        document.getElementById('errorContent').classList.add('show');
        document.getElementById('content').style.borderColor = '#d32f2f';
        isValid = false;
    } else {
        document.getElementById('content').style.borderColor = '#e0e0e0';
    }
    
    // Validation de l'image
    const imageInput = document.getElementById('image');
    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!allowedExtensions.includes(fileExtension)) {
            document.getElementById('errorImage').textContent = 'Format d\'image invalide. Formats autorisés : JPG, JPEG, PNG, GIF.';
            document.getElementById('errorImage').classList.add('show');
            isValid = false;
        }
        
        // Vérifier la taille (max 5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            document.getElementById('errorImage').textContent = 'L\'image est trop volumineuse. Taille maximale : 5MB.';
            document.getElementById('errorImage').classList.add('show');
            isValid = false;
        }
    }
    
    return isValid;
}

// ========================================
// Validation pour updatePost.php
// ========================================
function validateUpdatePost() {
    let isValid = true;
    
    // Reset error messages
    document.getElementById('errorContent').textContent = '';
    document.getElementById('errorImage').textContent = '';
    document.getElementById('errorContent').classList.remove('show');
    document.getElementById('errorImage').classList.remove('show');
    
    // Validation du contenu
    const content = document.getElementById('content').value.trim();
    
    if (content === '') {
        document.getElementById('errorContent').textContent = 'Le contenu est obligatoire.';
        document.getElementById('errorContent').classList.add('show');
        document.getElementById('content').style.borderColor = '#d32f2f';
        isValid = false;
    } else if (content.length > 500) {
        document.getElementById('errorContent').textContent = 'Le contenu ne doit pas dépasser 500 caractères.';
        document.getElementById('errorContent').classList.add('show');
        document.getElementById('content').style.borderColor = '#d32f2f';
        isValid = false;
    } else {
        document.getElementById('content').style.borderColor = '#e0e0e0';
    }
    
    // Validation de l'image (si une nouvelle est uploadée)
    const imageInput = document.getElementById('image');
    if (imageInput.files.length > 0) {
        const file = imageInput.files[0];
        const allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!allowedExtensions.includes(fileExtension)) {
            document.getElementById('errorImage').textContent = 'Format d\'image invalide. Formats autorisés : JPG, JPEG, PNG, GIF.';
            document.getElementById('errorImage').classList.add('show');
            isValid = false;
        }
        
        // Vérifier la taille (max 5MB)
        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            document.getElementById('errorImage').textContent = 'L\'image est trop volumineuse. Taille maximale : 5MB.';
            document.getElementById('errorImage').classList.add('show');
            isValid = false;
        }
    }
    
    return isValid;
}

// ========================================
// Compteur de caractères
// ========================================
function updateCharCount() {
    const content = document.getElementById('content').value;
    const charCount = document.getElementById('charCount');
    charCount.textContent = content.length;
    
    // Changer la couleur si proche de la limite
    if (content.length > 450) {
        charCount.style.color = '#d32f2f';
    } else if (content.length > 400) {
        charCount.style.color = '#ff9800';
    } else {
        charCount.style.color = '#7f8c8d';
    }
}

// ========================================
// Aperçu de l'image
// ========================================
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');
    const imagePreview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        // Mettre à jour le nom du fichier
        fileName.textContent = file.name;
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        
        reader.readAsDataURL(file);
    }
}

// ========================================
// Supprimer l'aperçu de l'image
// ========================================
function removeImage() {
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const fileName = document.getElementById('fileName');
    
    imageInput.value = '';
    imagePreview.style.display = 'none';
    fileName.textContent = 'Choisir une image';
}

// ========================================
// Toggle Menu Dropdown
// ========================================
function toggleMenu(postId) {
    const menu = document.getElementById('menu-' + postId);
    const allMenus = document.querySelectorAll('.dropdown-menu');
    
    // Fermer tous les autres menus
    allMenus.forEach(m => {
        if (m.id !== 'menu-' + postId) {
            m.classList.remove('show');
        }
    });
    
    // Toggle le menu actuel
    menu.classList.toggle('show');
}

// Fermer le menu si on clique ailleurs
document.addEventListener('click', function(event) {
    if (!event.target.closest('.post-actions-menu')) {
        const allMenus = document.querySelectorAll('.dropdown-menu');
        allMenus.forEach(m => m.classList.remove('show'));
    }
});

// ========================================
// Fonction Like (simulation)
// ========================================
function likePost(postId) {
    const likesElement = document.getElementById('likes-' + postId);
    if (!likesElement) {
        // Pour la page showPost.php
        const likesElementAlt = document.getElementById('likes');
        if (likesElementAlt) {
            let currentLikes = parseInt(likesElementAlt.textContent);
            likesElementAlt.textContent = currentLikes + 1;
            
            // Animation
            likesElementAlt.style.transform = 'scale(1.3)';
            setTimeout(() => {
                likesElementAlt.style.transform = 'scale(1)';
            }, 300);
        }
        return;
    }
    
    let currentLikes = parseInt(likesElement.textContent);
    likesElement.textContent = currentLikes + 1;
    
    // Animation
    likesElement.style.transform = 'scale(1.3)';
    likesElement.style.color = '#e91e63';
    setTimeout(() => {
        likesElement.style.transform = 'scale(1)';
    }, 300);
}

// ========================================
// Modal Image
// ========================================
function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    
    modal.style.display = 'block';
    modalImg.src = imageSrc;
    
    // Empêcher le scroll du body
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.style.display = 'none';
    
    // Réactiver le scroll
    document.body.style.overflow = 'auto';
}

// Fermer avec la touche Escape
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeImageModal();
    }
});

// ========================================
// FONCTIONS COMMENTAIRES
// ========================================

// Toggle Comments Section
function toggleComments(postId) {
    const commentsSection = document.getElementById('comments-' + postId);
    
    if (commentsSection.style.display === 'none') {
        commentsSection.style.display = 'block';
    } else {
        commentsSection.style.display = 'none';
    }
}

// Validate Comment
function validateComment(postId) {
    const commentText = document.getElementById('comment-text-' + postId).value.trim();
    const errorElement = document.getElementById('error-comment-' + postId);
    
    errorElement.textContent = '';
    errorElement.classList.remove('show');
    
    if (commentText === '') {
        errorElement.textContent = 'Le commentaire ne peut pas être vide.';
        errorElement.classList.add('show');
        return false;
    }
    
    if (commentText.length > 500) {
        errorElement.textContent = 'Le commentaire ne doit pas dépasser 500 caractères.';
        errorElement.classList.add('show');
        return false;
    }
    
    return true;
}

// Edit Comment
function editComment(commentId) {
    const textDisplay = document.getElementById('comment-text-display-' + commentId);
    const editForm = document.getElementById('edit-form-' + commentId);
    const actions = textDisplay.nextElementSibling.nextElementSibling;
    
    textDisplay.style.display = 'none';
    actions.style.display = 'none';
    editForm.style.display = 'block';
}

// Cancel Edit Comment
function cancelEditComment(commentId) {
    const textDisplay = document.getElementById('comment-text-display-' + commentId);
    const editForm = document.getElementById('edit-form-' + commentId);
    const actions = textDisplay.nextElementSibling.nextElementSibling;
    const errorElement = document.getElementById('error-edit-' + commentId);
    
    editForm.style.display = 'none';
    textDisplay.style.display = 'block';
    actions.style.display = 'flex';
    
    errorElement.textContent = '';
    errorElement.classList.remove('show');
}

// Save Comment
function saveComment(commentId) {
    const newText = document.getElementById('edit-text-' + commentId).value.trim();
    const errorElement = document.getElementById('error-edit-' + commentId);
    
    errorElement.textContent = '';
    errorElement.classList.remove('show');
    
    if (newText === '') {
        errorElement.textContent = 'Le commentaire ne peut pas être vide.';
        errorElement.classList.add('show');
        return;
    }
    
    if (newText.length > 500) {
        errorElement.textContent = 'Le commentaire ne doit pas dépasser 500 caractères.';
        errorElement.classList.add('show');
        return;
    }
    
    const formData = new FormData();
    formData.append('id', commentId);
    formData.append('text', newText);
    
    fetch('updateComment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'affichage
            const textDisplay = document.getElementById('comment-text-display-' + commentId);
            textDisplay.innerHTML = newText.replace(/\n/g, '<br>');
            
            cancelEditComment(commentId);
            
            // Notification de succès
            showNotification('Commentaire modifié avec succès !', 'success');
        } else {
            errorElement.textContent = data.message;
            errorElement.classList.add('show');
        }
    })
    .catch(error => {
        errorElement.textContent = 'Erreur de connexion.';
        errorElement.classList.add('show');
        console.error('Fetch error:', error);
    });
}

// Delete Comment
function deleteComment(commentId, postId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('id', commentId);
    
    fetch('deleteComment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Supprimer visuellement le commentaire
            const commentItem = document.getElementById('comment-item-' + commentId);
            commentItem.style.opacity = '0';
            commentItem.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                commentItem.remove();
                
                // Mettre à jour le compteur
                const commentCountElement = document.getElementById('comment-count-' + postId);
                if (commentCountElement) {
                    let currentCount = parseInt(commentCountElement.textContent);
                    commentCountElement.textContent = currentCount - 1;
                }
                
                // Vérifier s'il reste des commentaires
                const commentsList = commentItem.parentElement;
                if (commentsList.children.length === 0) {
                    commentsList.innerHTML = '<p class="no-comments">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>';
                }
            }, 300);
            
            showNotification('Commentaire supprimé !', 'success');
        } else {
            showNotification('Erreur lors de la suppression.', 'error');
        }
    })
    .catch(error => {
        showNotification('Erreur de connexion.', 'error');
        console.error('Fetch error:', error);
    });
}

// Show Notification
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = 'notification notification-' + type;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${message}</span>
    `;
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4caf50' : '#f44336'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 10000;
        animation: slideInRight 0.3s ease;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Ajouter les animations CSS pour les notifications
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ========================================
// Initialisation
// ========================================
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser le compteur de caractères si le textarea existe
    const contentTextarea = document.getElementById('content');
    if (contentTextarea) {
        updateCharCount();
    }
    
    // Animation au chargement des posts
    const postCards = document.querySelectorAll('.post-card');
    postCards.forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
    });
});