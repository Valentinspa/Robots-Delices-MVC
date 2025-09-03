
 /* SCRIPT DE CONFIRMATION DE SUPPRESSION DE COMPTEn */

// 1. On attend que la page soit complètement chargée avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function() {
    
    // 2. On cherche le bouton "Supprimer" dans la page
    const deleteBtn = document.querySelector('.btn-supprimer');
    
    // 3. On vérifie que le bouton existe bien (au cas où)
    if (deleteBtn) {
        
        // 4. On "écoute" les clics sur ce bouton
        deleteBtn.addEventListener('click', function(e) {
            
            // 5. On empêche l'action par défaut (aller vers le lien)
            e.preventDefault();
            
            // 6. On récupère l'URL de suppression depuis le lien
            const deleteUrl = this.href;
            
            // 7. On crée la modale personnalisée avec du HTML
            const modal = document.createElement('div');
            modal.className = 'delete-modal-overlay';
            modal.innerHTML = `
                <div class="delete-modal">
                    
                    <div class="delete-modal-body">
                        <p><strong>Êtes-vous absolument sûr ?</strong></p>
                        <p>Cette action supprimera définitivement votre compte et toutes les données associées.</p>
                        
                        <div class="delete-modal-warning">
                            <strong>Cette action est irréversible :</strong>
                            <ul>
                                <li>Toutes vos informations personnelles seront perdues</li>
                                <li>Vous ne pourrez plus vous connecter à votre compte</li>
                                <li>Aucune récupération ne sera possible</li>
                            </ul>
                        </div>
                        
                        <p>Voulez-vous vraiment continuer ?</p>
                    </div>
                    
                    <div class="delete-modal-footer">
                        <button type="button" class="modal-btn modal-btn-cancel">Annuler</button>
                        <button type="button" class="modal-btn modal-btn-delete">Supprimer définitivement</button>
                    </div>
                </div>
            `;
            
            // 8. On ajoute la modale à la page
            document.body.appendChild(modal);
            
            // 9. On récupère les boutons de la modale
            const confirmBtn = modal.querySelector('.modal-btn-delete');
            const cancelBtn = modal.querySelector('.modal-btn-cancel');
            
            // 10. On donne le focus au bouton Annuler (plus sûr)
            setTimeout(() => cancelBtn.focus(), 100);
            
            // 11. GESTION DU BOUTON ANNULER
            cancelBtn.addEventListener('click', function() {
                // On supprime la modale de la page
                document.body.removeChild(modal);
            });
            
            // 12. FERMETURE EN CLIQUANT EN DEHORS DE LA MODALE
            modal.addEventListener('click', function(e) {
                // Si on clique sur l'overlay (pas sur la modale elle-même)
                if (e.target === modal) {
                    // On simule un clic sur Annuler
                    cancelBtn.click();
                }
            });
            
            // 13. GESTION DU BOUTON DE CONFIRMATION
            confirmBtn.addEventListener('click', function() {
                // On change le texte du bouton pour montrer que ça se passe
                this.innerHTML = 'Suppression en cours...';
                this.disabled = true; // On désactive le bouton
                this.style.opacity = '0.7'; // On le rend transparent
                
                // On redirige vers l'URL de suppression après un court délai
                setTimeout(() => {
                    window.location.href = deleteUrl;
                }, 800); // 800ms = 0.8 seconde
            });
            
            // 14. FERMETURE AVEC LA TOUCHE ÉCHAP (Escape)
            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    // On simule un clic sur Annuler
                    cancelBtn.click();
                    // On supprime ce listener pour éviter les conflits
                    document.removeEventListener('keydown', escHandler);
                }
            });
        });
    }
});