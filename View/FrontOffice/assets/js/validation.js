document.addEventListener("DOMContentLoaded", function () {
    // VALIDATION FORMULAIRE QUIZ
    const formQuiz = document.getElementById("formQuiz");
    if (formQuiz) {
        formQuiz.addEventListener("submit", function (e) {
            let erreurs = [];
            
            // Validation du titre
            const titre = formQuiz.querySelector("[name='titre']");
            if (!titre || !titre.value.trim()) {
                erreurs.push("Le titre du quiz est obligatoire.");
                marquerErreur(titre);
            } else if (titre.value.trim().length < 3) {
                erreurs.push("Le titre doit contenir au moins 3 caractères.");
                marquerErreur(titre);
            } else {
                enleverErreur(titre);
            }
            
            // Validation de la catégorie
            const categorie = formQuiz.querySelector("[name='categorie']");
            if (!categorie || !categorie.value.trim()) {
                erreurs.push("Veuillez sélectionner une catégorie.");
                marquerErreur(categorie);
            } else {
                enleverErreur(categorie);
            }
            
            // Validation de la durée
            const duree = formQuiz.querySelector("[name='duree_estimee']");
            if (duree && duree.value) {
                const dureeVal = parseInt(duree.value);
                if (isNaN(dureeVal) || dureeVal < 1 || dureeVal > 60) {
                    erreurs.push("La durée doit être entre 1 et 60 minutes.");
                    marquerErreur(duree);
                } else {
                    enleverErreur(duree);
                }
            }
            
            // Afficher erreurs ou soumettre
            if (erreurs.length > 0) {
                e.preventDefault();
                afficherAlertErreurs(erreurs, formQuiz);
            }
        });
    }
    // VALIDATION FORMULAIRE QUESTION
    const formQuestion = document.getElementById("formQuestion");
    if (formQuestion) {
        formQuestion.addEventListener("submit", function (e) {
            let erreurs = [];
            
            // Validation du quiz sélectionné
            const idQuiz = formQuestion.querySelector("[name='id_quiz']");
            if (!idQuiz || !idQuiz.value || idQuiz.value === "") {
                erreurs.push("Veuillez sélectionner un quiz.");
                marquerErreur(idQuiz);
            } else {
                enleverErreur(idQuiz);
            }
            
            // Validation du texte de la question
            const texteQuestion = formQuestion.querySelector("[name='texte_question']");
            if (!texteQuestion || !texteQuestion.value.trim()) {
                erreurs.push("Le texte de la question est obligatoire.");
                marquerErreur(texteQuestion);
            } else if (texteQuestion.value.trim().length < 10) {
                erreurs.push("La question doit contenir au moins 10 caractères.");
                marquerErreur(texteQuestion);
            } else {
                enleverErreur(texteQuestion);
            }
            
            // Validation de l'ordre
            const ordre = formQuestion.querySelector("[name='ordre']");
            if (!ordre || !ordre.value.trim()) {
                erreurs.push("L'ordre est obligatoire.");
                marquerErreur(ordre);
            } else {
                const ordreVal = parseInt(ordre.value);
                if (isNaN(ordreVal) || ordreVal < 1) {
                    erreurs.push("L'ordre doit être un nombre positif.");
                    marquerErreur(ordre);
                } else {
                    enleverErreur(ordre);
                }
            }
            
            // Validation des 3 options obligatoires
            for (let i = 1; i <= 3; i++) {
                const optTexte = formQuestion.querySelector(`[name='option${i}_texte']`);
                const optScore = formQuestion.querySelector(`[name='option${i}_score']`);
                
                if (!optTexte || !optTexte.value.trim()) {
                    erreurs.push(`L'option ${i} : le texte est obligatoire.`);
                    marquerErreur(optTexte);
                } else {
                    enleverErreur(optTexte);
                }
                
                if (!optScore || !optScore.value.trim()) {
                    erreurs.push(`L'option ${i} : le score est obligatoire.`);
                    marquerErreur(optScore);
                } else {
                    const scoreVal = parseInt(optScore.value);
                    if (isNaN(scoreVal) || scoreVal < 1 || scoreVal > 10) {
                        erreurs.push(`L'option ${i} : le score doit être entre 1 et 10.`);
                        marquerErreur(optScore);
                    } else {
                        enleverErreur(optScore);
                    }
                }
            }
            
            // Option 4 facultative mais si texte présent, score obligatoire
            const opt4Texte = formQuestion.querySelector("[name='option4_texte']");
            const opt4Score = formQuestion.querySelector("[name='option4_score']");
            if (opt4Texte && opt4Texte.value.trim()) {
                if (!opt4Score || !opt4Score.value.trim()) {
                    erreurs.push("L'option 4 : le score est obligatoire si le texte est rempli.");
                    marquerErreur(opt4Score);
                } else {
                    const scoreVal = parseInt(opt4Score.value);
                    if (isNaN(scoreVal) || scoreVal < 1 || scoreVal > 10) {
                        erreurs.push("L'option 4 : le score doit être entre 1 et 10.");
                        marquerErreur(opt4Score);
                    } else {
                        enleverErreur(opt4Score);
                    }
                }
            }
            
            // Afficher erreurs ou soumettre
            if (erreurs.length > 0) {
                e.preventDefault();
                afficherAlertErreurs(erreurs, formQuestion);
            }
        });
    }
    // VALIDATION FORMULAIRE TEST (Front Office)
    const formTest = document.getElementById("formTest");
    if (formTest) {
        formTest.addEventListener("submit", function (e) {
            let erreurs = [];
            
            // Récupérer toutes les questions
            const questionBlocks = formTest.querySelectorAll(".question-block");
            const totalQuestions = questionBlocks.length;
            
            questionBlocks.forEach((block, index) => {
                const radios = block.querySelectorAll("input[type='radio']");
                const questionNum = index + 1;
                let repondu = false;
                
                radios.forEach(radio => {
                    if (radio.checked) repondu = true;
                });
                
                if (!repondu) {
                    erreurs.push(`Question ${questionNum} : veuillez sélectionner une réponse.`);
                    block.classList.add("border-danger");
                } else {
                    block.classList.remove("border-danger");
                }
            });
            
            if (erreurs.length > 0) {
                e.preventDefault();
                afficherAlertErreurs(erreurs, formTest);
                
                // Scroll vers la première question non répondue
                const premierErreur = formTest.querySelector(".border-danger");
                if (premierErreur) {
                    premierErreur.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        // Mise à jour barre de progression en temps réel
        formTest.addEventListener("change", function() {
            const totalQuestions = formTest.querySelectorAll(".question-block").length;
            const answeredQuestions = formTest.querySelectorAll("input[type='radio']:checked").length;
            const progress = (answeredQuestions / totalQuestions) * 100;
            
            const progressBar = document.getElementById("progressBar");
            const progressText = document.getElementById("progressText");
            
            if (progressBar && progressText) {
                progressBar.style.width = progress + "%";
                progressText.textContent = `Question ${answeredQuestions} sur ${totalQuestions}`;
                
                if (answeredQuestions === totalQuestions) {
                    progressBar.classList.remove("bg-success");
                    progressBar.classList.add("bg-primary");
                } else {
                    progressBar.classList.remove("bg-primary");
                    progressBar.classList.add("bg-success");
                }
            }
        });
    }
});
// FONCTIONS UTILITAIRES

//Marquer un champ en erreur
function marquerErreur(element) {
    if (element) {
        element.classList.add("is-invalid");
        element.classList.remove("is-valid");
    }
}
//Enlever l'erreur d'un champ
function enleverErreur(element) {
    if (element) {
        element.classList.remove("is-invalid");
        element.classList.add("is-valid");
    }
}
//Afficher les erreurs dans une alerte
function afficherAlertErreurs(erreurs, form) {
    // Supprimer l'ancienne alerte si elle existe
    const ancienneAlerte = document.querySelector(".alert-validation");
    if (ancienneAlerte) ancienneAlerte.remove();
    
    // Créer le bloc d'alerte
    const alertBox = document.createElement("div");
    alertBox.className = "alert alert-danger alert-validation mt-3 animate__animated animate__shakeX";
    alertBox.innerHTML = `
        <button type="button" class="btn-close float-end" onclick="this.parentElement.remove()"></button>
        <h5><i class="fas fa-exclamation-triangle"></i> Erreurs détectées :</h5>
        <ul class="mb-0">
            ${erreurs.map(err => `<li>${err}</li>`).join("")}
        </ul>
    `;
    
    // Insérer avant le bouton submit
    const submitBtn = form.querySelector("button[type='submit']");
    if (submitBtn) {
        submitBtn.parentElement.insertBefore(alertBox, submitBtn);
    } else {
        form.appendChild(alertBox);
    }
    
    // Scroll vers l'alerte
    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    
    // Auto-suppression après 10 secondes
    setTimeout(() => {
        if (alertBox.parentElement) {
            alertBox.classList.add("animate__fadeOut");
            setTimeout(() => alertBox.remove(), 500);
        }
    }, 10000);
}
//Validation en temps réel (on blur)
document.addEventListener("blur", function(e) {
    const element = e.target;
    
    // Ne valider que les champs de formulaire
    if (!element.matches("input, select, textarea")) return;
    
    // Validation simple : champ non vide
    if (element.hasAttribute("data-validate")) {
        if (!element.value.trim()) {
            marquerErreur(element);
        } else {
            enleverErreur(element);
        }
    }
}, true);