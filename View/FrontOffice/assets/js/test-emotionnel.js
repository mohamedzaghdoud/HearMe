/**
 * JavaScript pour le test émotionnel (test_emotionnel.php)
 * HearMe FrontOffice
 */

document.addEventListener('DOMContentLoaded', function() {
    initProgressTracker();
    initOptionHighlight();
});

/**
 * Initialise le tracker de progression
 */
function initProgressTracker() {
    const form = document.getElementById('formTest');
    if (!form) return;
    
    const totalQuestions = parseInt(form.dataset.totalQuestions) || 
                          document.querySelectorAll('.question-block').length;
    const radios = document.querySelectorAll('input[type="radio"]');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    if (!progressBar || !progressText) return;
    
    function updateProgress() {
        const answered = new Set();
        radios.forEach(radio => {
            if (radio.checked) {
                answered.add(radio.name);
            }
        });
        
        const percent = (answered.size / totalQuestions) * 100;
        progressBar.style.width = percent + '%';
        progressText.textContent = 'Question ' + answered.size + ' sur ' + totalQuestions;
        
        // Change color based on progress
        if (percent === 100) {
            progressBar.classList.remove('bg-success');
            progressBar.classList.add('bg-primary');
        }
    }
    
    radios.forEach(radio => {
        radio.addEventListener('change', updateProgress);
    });
}

/**
 * Initialise la mise en surbrillance des options sélectionnées
 */
function initOptionHighlight() {
    const optionItems = document.querySelectorAll('.option-item');
    
    optionItems.forEach(item => {
        const radio = item.querySelector('input[type="radio"]');
        
        if (radio) {
            // Highlight on change
            radio.addEventListener('change', function() {
                // Remove highlight from siblings
                const parent = this.closest('.options-container');
                if (parent) {
                    parent.querySelectorAll('.option-item').forEach(opt => {
                        opt.style.background = '#f8f9fa';
                        opt.style.borderColor = 'transparent';
                    });
                }
                
                // Add highlight to selected
                if (this.checked) {
                    item.style.background = '#e3f2fd';
                    item.style.borderColor = '#A7C7E7';
                }
            });
            
            // Click on entire option item
            item.addEventListener('click', function(e) {
                if (e.target !== radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change'));
                }
            });
        }
    });
}

/**
 * Validation du formulaire avant soumission
 */
function validateTestForm() {
    const form = document.getElementById('formTest');
    if (!form) return true;
    
    const questionBlocks = document.querySelectorAll('.question-block');
    let allAnswered = true;
    
    questionBlocks.forEach((block, index) => {
        const radios = block.querySelectorAll('input[type="radio"]');
        const isAnswered = Array.from(radios).some(r => r.checked);
        
        if (!isAnswered) {
            allAnswered = false;
            block.style.borderColor = '#EF5350';
            block.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            block.style.borderColor = '#e0e0e0';
        }
    });
    
    if (!allAnswered) {
        alert('Veuillez répondre à toutes les questions avant de soumettre.');
        return false;
    }
    
    return true;
}
