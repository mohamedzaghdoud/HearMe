import React, { useState, useRef, useEffect } from 'react';
import { Camera, CheckCircle, XCircle, AlertCircle, User, Lock, Unlock } from 'lucide-react';

const FaceIDSystem = () => {
  const [mode, setMode] = useState('menu'); // menu, register, login
  const [status, setStatus] = useState('');
  const [statusType, setStatusType] = useState('info');
  const [model, setModel] = useState(null);
  const [stream, setStream] = useState(null);
  const [faceDetected, setFaceDetected] = useState(false);
  const [registeredFaces, setRegisteredFaces] = useState([]);
  const [currentUser, setCurrentUser] = useState(null);
  
  const videoRef = useRef(null);
  const canvasRef = useRef(null);
  const animationRef = useRef(null);

  useEffect(() => {
    loadModel();
    loadRegisteredFaces();
    return () => {
      stopCamera();
    };
  }, []);

  const loadModel = async () => {
    try {
      setStatus('🤖 Chargement du modèle IA...');
      setStatusType('loading');
      
      const blazeface = window.blazeface;
      if (!blazeface) {
        throw new Error('BlazeFace non disponible');
      }
      
      const loadedModel = await blazeface.load();
      setModel(loadedModel);
      setStatus('✅ Modèle chargé avec succès!');
      setStatusType('success');
    } catch (error) {
      console.error('Erreur chargement modèle:', error);
      setStatus('❌ Erreur de chargement du modèle');
      setStatusType('error');
    }
  };

  const loadRegisteredFaces = async () => {
    try {
      const result = await window.storage.get('registered_faces');
      if (result) {
        setRegisteredFaces(JSON.parse(result.value));
      }
    } catch (error) {
      console.log('Aucune face enregistrée');
      setRegisteredFaces([]);
    }
  };

  const startCamera = async () => {
    try {
      setStatus('📷 Démarrage de la caméra...');
      setStatusType('loading');
      
      const mediaStream = await navigator.mediaDevices.getUserMedia({
        video: {
          width: { ideal: 640 },
          height: { ideal: 480 },
          facingMode: 'user'
        }
      });
      
      setStream(mediaStream);
      
      if (videoRef.current) {
        videoRef.current.srcObject = mediaStream;
        videoRef.current.onloadedmetadata = () => {
          videoRef.current.play();
          if (canvasRef.current) {
            canvasRef.current.width = videoRef.current.videoWidth;
            canvasRef.current.height = videoRef.current.videoHeight;
          }
          detectFaces();
        };
      }
      
      setStatus('👤 Placez votre visage au centre');
      setStatusType('info');
    } catch (error) {
      console.error('Erreur caméra:', error);
      setStatus('❌ Impossible d\'accéder à la caméra');
      setStatusType('error');
    }
  };

  const stopCamera = () => {
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
      setStream(null);
    }
    if (animationRef.current) {
      cancelAnimationFrame(animationRef.current);
    }
    if (videoRef.current) {
      videoRef.current.srcObject = null;
    }
  };

  const detectFaces = async () => {
    if (!model || !videoRef.current) return;

    const predictions = await model.estimateFaces(videoRef.current, false);
    const canvas = canvasRef.current;
    const ctx = canvas?.getContext('2d');

    if (ctx) {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      if (predictions.length > 0) {
        setFaceDetected(true);
        predictions.forEach(prediction => {
          const start = prediction.topLeft;
          const end = prediction.bottomRight;
          const size = [end[0] - start[0], end[1] - start[1]];

          // Dessiner rectangle vert
          ctx.strokeStyle = '#10B981';
          ctx.lineWidth = 4;
          ctx.strokeRect(start[0], start[1], size[0], size[1]);

          // Dessiner coins arrondis
          const cornerSize = 20;
          ctx.strokeStyle = '#10B981';
          ctx.lineWidth = 6;
          
          // Coin haut gauche
          ctx.beginPath();
          ctx.moveTo(start[0] + cornerSize, start[1]);
          ctx.lineTo(start[0], start[1]);
          ctx.lineTo(start[0], start[1] + cornerSize);
          ctx.stroke();
          
          // Coin haut droit
          ctx.beginPath();
          ctx.moveTo(end[0] - cornerSize, start[1]);
          ctx.lineTo(end[0], start[1]);
          ctx.lineTo(end[0], start[1] + cornerSize);
          ctx.stroke();
          
          // Coin bas gauche
          ctx.beginPath();
          ctx.moveTo(start[0], end[1] - cornerSize);
          ctx.lineTo(start[0], end[1]);
          ctx.lineTo(start[0] + cornerSize, end[1]);
          ctx.stroke();
          
          // Coin bas droit
          ctx.beginPath();
          ctx.moveTo(end[0] - cornerSize, end[1]);
          ctx.lineTo(end[0], end[1]);
          ctx.lineTo(end[0], end[1] - cornerSize);
          ctx.stroke();

          // Points clés
          const landmarks = prediction.landmarks;
          ctx.fillStyle = '#EF4444';
          landmarks.forEach(landmark => {
            ctx.beginPath();
            ctx.arc(landmark[0], landmark[1], 4, 0, 2 * Math.PI);
            ctx.fill();
          });
        });

        if (mode === 'register') {
          setStatus('✅ Visage détecté! Cliquez sur "Enregistrer"');
        } else if (mode === 'login') {
          setStatus('✅ Visage détecté! Cliquez sur "Se connecter"');
        }
        setStatusType('success');
      } else {
        setFaceDetected(false);
        setStatus('⚠️ Aucun visage détecté');
        setStatusType('warning');
      }
    }

    animationRef.current = requestAnimationFrame(detectFaces);
  };

  const captureFaceData = async () => {
    if (!model || !videoRef.current) return null;

    const predictions = await model.estimateFaces(videoRef.current, false);
    if (predictions.length === 0) return null;

    const prediction = predictions[0];
    return {
      landmarks: prediction.landmarks,
      probability: prediction.probability,
      box: {
        topLeft: prediction.topLeft,
        bottomRight: prediction.bottomRight
      },
      timestamp: Date.now()
    };
  };

  const handleRegister = async () => {
    try {
      setStatus('🔍 Capture du visage...');
      setStatusType('loading');

      const faceData = await captureFaceData();
      if (!faceData) {
        setStatus('❌ Aucun visage détecté');
        setStatusType('error');
        return;
      }

      const email = prompt('Entrez votre email pour l\'enregistrement:');
      if (!email) return;

      const newFace = {
        id: Date.now(),
        email,
        faceData,
        registeredAt: new Date().toISOString()
      };

      const updatedFaces = [...registeredFaces, newFace];
      setRegisteredFaces(updatedFaces);

      await window.storage.set('registered_faces', JSON.stringify(updatedFaces));

      setStatus('✅ Visage enregistré avec succès!');
      setStatusType('success');
      
      setTimeout(() => {
        stopCamera();
        setMode('menu');
      }, 2000);
    } catch (error) {
      console.error('Erreur enregistrement:', error);
      setStatus('❌ Erreur lors de l\'enregistrement');
      setStatusType('error');
    }
  };

  const compareFaceData = (face1, face2) => {
    if (!face1.landmarks || !face2.landmarks) return 0;
    
    let totalDistance = 0;
    const landmarks1 = face1.landmarks;
    const landmarks2 = face2.landmarks;
    
    if (landmarks1.length !== landmarks2.length) return 0;
    
    for (let i = 0; i < landmarks1.length; i++) {
      const dx = landmarks1[i][0] - landmarks2[i][0];
      const dy = landmarks1[i][1] - landmarks2[i][1];
      totalDistance += Math.sqrt(dx * dx + dy * dy);
    }
    
    const avgDistance = totalDistance / landmarks1.length;
    const similarity = Math.max(0, 1 - (avgDistance / 100));
    
    return similarity;
  };

  const handleLogin = async () => {
    try {
      setStatus('🔍 Analyse du visage...');
      setStatusType('loading');

      const faceData = await captureFaceData();
      if (!faceData) {
        setStatus('❌ Aucun visage détecté');
        setStatusType('error');
        return;
      }

      if (registeredFaces.length === 0) {
        setStatus('❌ Aucun visage enregistré dans la base');
        setStatusType('error');
        return;
      }

      let bestMatch = null;
      let bestSimilarity = 0;

      for (const registered of registeredFaces) {
        const similarity = compareFaceData(faceData, registered.faceData);
        if (similarity > bestSimilarity) {
          bestSimilarity = similarity;
          bestMatch = registered;
        }
      }

      const threshold = 0.75;
      if (bestSimilarity >= threshold) {
        setStatus(`✅ Connexion réussie! Bienvenue ${bestMatch.email}`);
        setStatusType('success');
        setCurrentUser(bestMatch);
        
        setTimeout(() => {
          stopCamera();
          setMode('success');
        }, 2000);
      } else {
        setStatus('❌ Visage non reconnu');
        setStatusType('error');
      }
    } catch (error) {
      console.error('Erreur connexion:', error);
      setStatus('❌ Erreur lors de la connexion');
      setStatusType('error');
    }
  };

  const resetSystem = () => {
    stopCamera();
    setMode('menu');
    setCurrentUser(null);
    setStatus('');
  };

  const clearAllFaces = async () => {
    if (confirm('Êtes-vous sûr de vouloir supprimer tous les visages enregistrés?')) {
      try {
        await window.storage.delete('registered_faces');
        setRegisteredFaces([]);
        setStatus('✅ Tous les visages ont été supprimés');
        setStatusType('success');
      } catch (error) {
        setStatus('❌ Erreur lors de la suppression');
        setStatusType('error');
      }
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-4 flex items-center justify-center">
      <div className="max-w-2xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden">
        {/* Header */}
        <div className="bg-gradient-to-r from-indigo-600 to-purple-600 p-6 text-white">
          <div className="flex items-center justify-between">
            <div>
              <h1 className="text-3xl font-bold flex items-center gap-2">
                <Camera className="w-8 h-8" />
                Face ID System
              </h1>
              <p className="text-indigo-100 mt-1">Reconnaissance faciale sécurisée</p>
            </div>
            {registeredFaces.length > 0 && (
              <div className="text-right">
                <div className="text-2xl font-bold">{registeredFaces.length}</div>
                <div className="text-sm text-indigo-100">visages enregistrés</div>
              </div>
            )}
          </div>
        </div>

        <div className="p-6">
          {/* Status */}
          {status && (
            <div className={`mb-6 p-4 rounded-xl flex items-center gap-3 ${
              statusType === 'success' ? 'bg-green-50 text-green-700 border border-green-200' :
              statusType === 'error' ? 'bg-red-50 text-red-700 border border-red-200' :
              statusType === 'warning' ? 'bg-yellow-50 text-yellow-700 border border-yellow-200' :
              'bg-blue-50 text-blue-700 border border-blue-200'
            }`}>
              {statusType === 'success' && <CheckCircle className="w-5 h-5" />}
              {statusType === 'error' && <XCircle className="w-5 h-5" />}
              {statusType === 'warning' && <AlertCircle className="w-5 h-5" />}
              {statusType === 'loading' && <div className="w-5 h-5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin" />}
              <span className="font-medium">{status}</span>
            </div>
          )}

          {/* Menu Principal */}
          {mode === 'menu' && (
            <div className="space-y-4">
              <div className="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                <h2 className="text-xl font-bold text-gray-800 mb-4">Instructions</h2>
                <ul className="space-y-2 text-gray-600">
                  <li className="flex items-start gap-2">
                    <span className="text-indigo-500 font-bold">1.</span>
                    <span>Autorisez l'accès à votre caméra</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <span className="text-indigo-500 font-bold">2.</span>
                    <span>Placez votre visage au centre de l'écran</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <span className="text-indigo-500 font-bold">3.</span>
                    <span>Regardez directement la caméra</span>
                  </li>
                  <li className="flex items-start gap-2">
                    <span className="text-indigo-500 font-bold">4.</span>
                    <span>Attendez la détection automatique</span>
                  </li>
                </ul>
              </div>

              <div className="grid grid-cols-2 gap-4">
                <button
                  onClick={() => {
                    setMode('register');
                    startCamera();
                  }}
                  className="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-6 rounded-2xl hover:from-green-600 hover:to-emerald-600 transition-all transform hover:scale-105 shadow-lg"
                >
                  <User className="w-8 h-8 mx-auto mb-2" />
                  <div className="font-bold text-lg">Enregistrer</div>
                  <div className="text-sm text-green-100">Nouveau visage</div>
                </button>

                <button
                  onClick={() => {
                    setMode('login');
                    startCamera();
                  }}
                  disabled={registeredFaces.length === 0}
                  className="bg-gradient-to-r from-blue-500 to-indigo-500 text-white p-6 rounded-2xl hover:from-blue-600 hover:to-indigo-600 transition-all transform hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <Unlock className="w-8 h-8 mx-auto mb-2" />
                  <div className="font-bold text-lg">Se connecter</div>
                  <div className="text-sm text-blue-100">Face ID</div>
                </button>
              </div>

              {registeredFaces.length > 0 && (
                <button
                  onClick={clearAllFaces}
                  className="w-full bg-red-50 text-red-600 p-4 rounded-xl hover:bg-red-100 transition-colors border border-red-200 font-medium"
                >
                  🗑️ Supprimer tous les visages
                </button>
              )}
            </div>
          )}

          {/* Mode Enregistrement / Connexion */}
          {(mode === 'register' || mode === 'login') && (
            <div className="space-y-4">
              <div className="relative rounded-2xl overflow-hidden bg-gray-900">
                <video
                  ref={videoRef}
                  className="w-full"
                  autoPlay
                  playsInline
                  muted
                />
                <canvas
                  ref={canvasRef}
                  className="absolute top-0 left-0 w-full h-full"
                />
              </div>

              <div className="flex gap-3">
                {mode === 'register' && (
                  <button
                    onClick={handleRegister}
                    disabled={!faceDetected}
                    className="flex-1 bg-gradient-to-r from-green-500 to-emerald-500 text-white py-4 px-6 rounded-xl font-bold hover:from-green-600 hover:to-emerald-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
                  >
                    ✅ Enregistrer ce visage
                  </button>
                )}

                {mode === 'login' && (
                  <button
                    onClick={handleLogin}
                    disabled={!faceDetected}
                    className="flex-1 bg-gradient-to-r from-blue-500 to-indigo-500 text-white py-4 px-6 rounded-xl font-bold hover:from-blue-600 hover:to-indigo-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-lg"
                  >
                    🔓 Se connecter
                  </button>
                )}

                <button
                  onClick={resetSystem}
                  className="bg-gray-100 text-gray-700 py-4 px-6 rounded-xl font-bold hover:bg-gray-200 transition-colors"
                >
                  ← Retour
                </button>
              </div>
            </div>
          )}

          {/* Écran de succès */}
          {mode === 'success' && currentUser && (
            <div className="text-center space-y-6 py-8">
              <div className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto animate-bounce">
                <CheckCircle className="w-12 h-12 text-green-600" />
              </div>
              
              <div>
                <h2 className="text-3xl font-bold text-gray-800 mb-2">
                  Connexion réussie! 🎉
                </h2>
                <p className="text-gray-600 text-lg">
                  Bienvenue, <span className="font-bold text-indigo-600">{currentUser.email}</span>
                </p>
              </div>

              <div className="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-100">
                <div className="text-sm text-gray-600 mb-2">Connecté depuis</div>
                <div className="text-lg font-bold text-gray-800">
                  {new Date(currentUser.registeredAt).toLocaleDateString('fr-FR', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                  })}
                </div>
              </div>

              <button
                onClick={resetSystem}
                className="bg-gradient-to-r from-indigo-500 to-purple-500 text-white py-4 px-8 rounded-xl font-bold hover:from-indigo-600 hover:to-purple-600 transition-all shadow-lg"
              >
                Retour au menu
              </button>
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="bg-gray-50 p-4 text-center text-sm text-gray-600 border-t">
          <Lock className="w-4 h-4 inline mr-2" />
          Système de reconnaissance faciale sécurisé - HearMe
        </div>
      </div>
    </div>
  );
};

export default FaceIDSystem;