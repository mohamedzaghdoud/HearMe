document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("formQuestion");
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let erreurs = [];

    // vérification du texte de la question
    const question = form.querySelector("[name='texte_question']");
    if (!question.value.trim()) {
      erreurs.push("Le texte de la question ne doit pas être vide.");
      question.classList.add("is-invalid");
    } else {
      question.classList.remove("is-invalid");
    }

    //Vérification de la catégorie
    const categorie = form.querySelector("[name='categorie']");
    if (!categorie.value.trim()) {
      erreurs.push("Veuillez sélectionner une catégorie.");
      categorie.classList.add("is-invalid");
    } else {
      categorie.classList.remove("is-invalid");
    }

    //Véification de l'ordre
    const ordre = form.querySelector("[name='ordre']");
    if (!ordre.value.trim()) {
      erreurs.push("Le champ 'Ordre' est obligatoire.");
      ordre.classList.add("is-invalid");
    } else if (isNaN(ordre.value) || parseInt(ordre.value) < 1) {
      erreurs.push("L'ordre doit être un nombre positif.");
      ordre.classList.add("is-invalid");
    } else {
      ordre.classList.remove("is-invalid");
    }

    //vérification des options
    const options = form.querySelectorAll(".option-item");
    if (options.length === 0) {
      erreurs.push("Ajoutez au moins une option de réponse.");
    }

    options.forEach((opt, index) => {
      const texte = opt.querySelector("input[name^='options'][name$='[texte]']");
      const score = opt.querySelector("input[name^='options'][name$='[score]']");

      if (!texte.value.trim()) {
        erreurs.push(`Option ${index + 1} : le texte est vide.`);
        texte.classList.add("is-invalid");
      } else {
        texte.classList.remove("is-invalid");
      }

      if (!score.value.trim()) {
        erreurs.push(`Option ${index + 1} : le score est vide.`);
        score.classList.add("is-invalid");
      } else if (isNaN(score.value) || parseInt(score.value) < 1 || parseInt(score.value) > 10) {
        erreurs.push(`Option ${index + 1} : le score doit être un nombre entre 1 et 10.`);
        score.classList.add("is-invalid");
      } else {
        score.classList.remove("is-invalid");
      }
    });

    //Gestion des erreurs
    if (erreurs.length > 0) {
      e.preventDefault(); // bloque l’envoi du formulaire
      afficherAlert(erreurs);
    } else {
      alert("✅ Formulaire validé avec succès !");
    }
  });
});

function afficherAlert(erreurs) {
  // Supprime d’anciennes alertes
  const ancienne = document.querySelector(".alert-validation");
  if (ancienne) ancienne.remove();

  // Crée un bloc d’alerte
  const alertBox = document.createElement("div");
  alertBox.className = "alert-validation alert alert-danger mt-3 animated fadeIn";
  alertBox.innerHTML = `
      <strong>⚠️ Des erreurs ont été détectées :</strong><br>
      <ul>${erreurs.map(err => `<li>${err}</li>`).join("")}</ul>
  `;

  // L’ajouter avant le bouton de soumission
  const form = document.getElementById("formQuestion");
  form.insertBefore(alertBox, form.querySelector("button[type='submit']"));

  // Animation douce (disparition après 6s)
  setTimeout(() => {
    alertBox.classList.add("fadeOut");
    setTimeout(() => alertBox.remove(), 800);
  }, 6000);
}
