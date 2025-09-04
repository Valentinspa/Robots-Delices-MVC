const link = document.querySelector('.btn-supprimer');

link?.addEventListener('click', confirmDeletion);

function confirmDeletion(event) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible.")) {
        event.preventDefault();
    }
}