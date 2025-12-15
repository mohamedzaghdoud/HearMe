/**
 * Face Login JS - HearMe
 */

class FaceIDSystem {
    constructor() {
        this.mode = 'menu';
        this.model = null;
        this.stream = null;
        this.faceDetected = false;
        this.currentFaceQuality = 0;
        this.animationRef = null;
        
        this.initElements();
        this.initEventListeners();
        this.loadModel();
    }
    
    initElements() {
        this.statusEl = document.getElementById('status');
        this.statusIcon = document.getElementById('statusIcon');
        this.statusText = document.getElementById('statusText');
        this.menuSection = document.getElementById('menuSection');
        this.cameraSection = document.getElementById('cameraSection');
        this.successSection = document.getElementById('successSection');
        this.videoElement = document.getElementById('videoElement');
        this.canvasElement = document.getElementById('canvasElement');
        this.ctx = this.canvasElement.getContext('2d');
        this.qualityText = document.getElementById('qualityText');
        this.actionBtn = document.getElementById('actionBtn');
    }
    
    initEventListeners() {
        document.getElementById('registerBtn').addEventListener('click', () => this.setMode('register'));
        document.getElementById('loginBtn').addEventListener('click', () => this.setMode('login'));
        document.getElementById('backBtn').addEventListener('click', () => this.resetSystem());
        document.getElementById('backToMenuBtn').addEventListener('click', () => this.resetSystem());
        this.actionBtn.addEventListener('click', () => {
            if (this.mode === 'register') this.handleRegister();
            else if (this.mode === 'login') this.handleLogin();
        });
    }
    
    async loadModel() {
        try {
            this.showStatus('🤖 Chargement du modèle IA...', 'loading');
            this.model = await blazeface.load();
            this.showStatus('✅ Modèle chargé!', 'success');
            setTimeout(() => this.hideStatus(), 2000);
        } catch (error) {
            this.showStatus('❌ Erreur de chargement', 'error');
        }
    }

    
    setMode(mode) {
        this.mode = mode;
        this.menuSection.classList.add('hidden');
        this.cameraSection.classList.remove('hidden');
        this.successSection.classList.add('hidden');
        
        if (mode === 'register') {
            this.actionBtn.textContent = '✅ Enregistrer ce visage';
            this.actionBtn.className = 'btn btn-register btn-action';
        } else {
            this.actionBtn.textContent = '🔓 Se connecter';
            this.actionBtn.className = 'btn btn-login btn-action';
        }
        
        this.startCamera();
    }
    
    showStatus(message, type) {
        this.statusText.textContent = message;
        this.statusEl.className = `status-box status-${type}`;
        this.statusEl.classList.remove('hidden');
        
        this.statusIcon.innerHTML = '';
        if (type === 'loading') {
            this.statusIcon.innerHTML = '<div class="spinner"></div>';
        }
    }
    
    hideStatus() {
        this.statusEl.classList.add('hidden');
    }
    
    async startCamera() {
        try {
            this.showStatus('📷 Démarrage caméra...', 'loading');
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 640 }, height: { ideal: 480 }, facingMode: 'user' }
            });
            this.videoElement.srcObject = this.stream;
            this.videoElement.onloadedmetadata = () => {
                this.videoElement.play();
                this.canvasElement.width = this.videoElement.videoWidth;
                this.canvasElement.height = this.videoElement.videoHeight;
                this.detectFaces();
            };
            this.showStatus('👤 Placez votre visage au centre', 'info');
        } catch (error) {
            this.showStatus('❌ Impossible d\'accéder à la caméra', 'error');
        }
    }
    
    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
        if (this.animationRef) cancelAnimationFrame(this.animationRef);
    }
    
    async detectFaces() {
        if (!this.model || !this.videoElement) return;
        
        const predictions = await this.model.estimateFaces(this.videoElement, false);
        this.ctx.clearRect(0, 0, this.canvasElement.width, this.canvasElement.height);
        
        if (predictions.length > 0) {
            this.faceDetected = true;
            this.actionBtn.disabled = false;
            
            const pred = predictions[0];
            const start = pred.topLeft;
            const end = pred.bottomRight;
            const size = [end[0] - start[0], end[1] - start[1]];
            
            this.currentFaceQuality = this.calculateQuality(pred, size);
            this.updateQuality(this.currentFaceQuality);
            
            this.ctx.strokeStyle = this.currentFaceQuality >= 0.7 ? '#10B981' : '#F59E0B';
            this.ctx.lineWidth = 4;
            this.ctx.strokeRect(start[0], start[1], size[0], size[1]);
            
            // Landmarks
            this.ctx.fillStyle = '#EF4444';
            pred.landmarks.forEach(lm => {
                this.ctx.beginPath();
                this.ctx.arc(lm[0], lm[1], 4, 0, 2 * Math.PI);
                this.ctx.fill();
            });
            
            if (this.currentFaceQuality >= 0.7) {
                this.showStatus('✅ Excellente qualité!', 'success');
            } else {
                this.showStatus('⚠️ Rapprochez-vous', 'warning');
            }
        } else {
            this.faceDetected = false;
            this.actionBtn.disabled = true;
            this.updateQuality(0);
            this.showStatus('⚠️ Aucun visage détecté', 'warning');
        }
        
        this.animationRef = requestAnimationFrame(() => this.detectFaces());
    }
    
    calculateQuality(pred, size) {
        const area = size[0] * size[1];
        const canvasArea = this.canvasElement.width * this.canvasElement.height;
        const sizeRatio = area / canvasArea;
        const sizeScore = sizeRatio >= 0.15 && sizeRatio <= 0.6 ? 1 : 0.5;
        const probScore = pred.probability[0] || 0;
        return (sizeScore * 0.4 + probScore * 0.6);
    }
    
    updateQuality(q) {
        if (q >= 0.8) this.qualityText.textContent = '🟢 Excellente';
        else if (q >= 0.6) this.qualityText.textContent = '🟡 Bonne';
        else if (q >= 0.4) this.qualityText.textContent = '🟠 Moyenne';
        else this.qualityText.textContent = '🔴 Faible';
    }
    
    async captureFaceData() {
        const predictions = await this.model.estimateFaces(this.videoElement, false);
        if (predictions.length === 0) return null;
        const pred = predictions[0];
        return {
            landmarks: pred.landmarks,
            probability: pred.probability,
            box: { topLeft: pred.topLeft, bottomRight: pred.bottomRight },
            quality: this.currentFaceQuality,
            timestamp: Date.now()
        };
    }
    
    async handleRegister() {
        if (this.currentFaceQuality < 0.6) {
            this.showStatus('⚠️ Qualité insuffisante', 'warning');
            return;
        }
        
        this.showStatus('📸 Capture...', 'loading');
        const faceData = await this.captureFaceData();
        if (!faceData) {
            this.showStatus('❌ Aucun visage', 'error');
            return;
        }
        
        const email = prompt('Entrez votre email:');
        if (!email) return;
        
        const formData = new FormData();
        formData.append('email', email);
        formData.append('face_data', JSON.stringify(faceData));
        
        try {
            const response = await fetch('/hearme_user/Controller/UserController.php?action=registerFace', {
                method: 'POST', body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                this.showStatus('✅ Visage enregistré!', 'success');
                setTimeout(() => this.resetSystem(), 2000);
            } else {
                this.showStatus('❌ ' + result.message, 'error');
            }
        } catch (e) {
            this.showStatus('❌ Erreur', 'error');
        }
    }
    
    async handleLogin() {
        if (this.currentFaceQuality < 0.5) {
            this.showStatus('⚠️ Qualité insuffisante', 'warning');
            return;
        }
        
        this.showStatus('🔍 Analyse...', 'loading');
        const faceData = await this.captureFaceData();
        if (!faceData) {
            this.showStatus('❌ Aucun visage', 'error');
            return;
        }
        
        const formData = new FormData();
        formData.append('face_data', JSON.stringify(faceData));
        
        try {
            const response = await fetch('/hearme_user/Controller/UserController.php?action=faceLogin', {
                method: 'POST', body: formData
            });
            const result = await response.json();
            
            if (result.success) {
                this.showStatus('✅ Connexion réussie!', 'success');
                setTimeout(() => {
                    window.location.href = result.redirect || '/hearme_user/View/FrontOffice/Home.php';
                }, 2000);
            } else {
                this.showStatus('❌ ' + result.message, 'error');
            }
        } catch (e) {
            this.showStatus('❌ Erreur', 'error');
        }
    }
    
    resetSystem() {
        this.stopCamera();
        this.mode = 'menu';
        this.menuSection.classList.remove('hidden');
        this.cameraSection.classList.add('hidden');
        this.successSection.classList.add('hidden');
        this.hideStatus();
    }
}

document.addEventListener('DOMContentLoaded', () => new FaceIDSystem());
