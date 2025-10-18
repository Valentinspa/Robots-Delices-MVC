<!--
    FICHIER : header.php
    RÔLE : Composant d'en-tête réutilisable pour toutes les pages du site
    
    Ce fichier contient la structure HTML de la barre de navigation du site Robots-Délices.
    Il génère dynamiquement les liens de navigation en fonction de l'état de connexion de l'utilisateur.
    Ajout d'un sous-menu sur le profil utilisateur contenant "Mes favoris", "Ajouter une recette" et "Déconnexion".
    
    CONCEPTS PHP UTILISÉS :
    - Variables de session ($_SESSION) : pour vérifier si un utilisateur est connecté et récupérer ses informations
    - Structures conditionnelles (if/else) : pour afficher différents menus selon l'état de connexion
    - Echo : pour générer du HTML dynamiquement
    - htmlspecialchars() : pour sécuriser l'affichage des données utilisateur
    
    SÉCURITÉ :
    - Le fichier vérifie l'existence de la variable de session 'user_id' pour déterminer si l'utilisateur est connecté
    - Utilisation de htmlspecialchars() pour éviter les attaques XSS lors de l'affichage du nom
-->

<header>
    <div id="section">
        <!-- Section du logo - Cliquable pour retourner à l'accueil -->
        <div id="header-title">
            <a href="/">
                <img id="logo" alt="Logo Robots-Délices" src="/assets/img/logo_robots_delices.png">
            </a>
        </div>

        <!-- Menu hamburger responsive - Case à cocher cachée pour gérer l'ouverture/fermeture -->
        <input id="menu-toggle" type="checkbox" />
        <label class="burger-menu" for="menu-toggle">
            <span class="burger-line"></span>
            <span class="burger-line"></span>
            <span class="burger-line"></span>
        </label>

        <!-- Container de la navigation principale -->
        <div id="nav-container" class="nav-container">
            <ul class="nav-menu">
                <!-- Liens de navigation communs à tous les utilisateurs -->
                <li class="li"><a href="/recettes">Recettes</a></li>
                <li class="li"><a href="/categories">Catégories</a></li>

                <?php
                // LOGIQUE DE NAVIGATION CONDITIONNELLE
                // Vérification si l'utilisateur est connecté en vérifiant la présence de 'user_id' dans la session
                if (isset($_SESSION['user_id'])) {
                    // Récupération des informations utilisateur depuis la session
                    // Les champs de la DB sont 'firstname' et 'lastname'
                    $firstname = isset($_SESSION['firstname']) ? $_SESSION['firstname'] : '';
                    $lastname = isset($_SESSION['lastname']) ? $_SESSION['lastname'] : '';

                    // Construction du nom complet avec sécurisation
                    $nomComplet = '';
                    if (!empty($firstname) && !empty($lastname)) {
                        $nomComplet = htmlspecialchars($firstname . ' ' . $lastname, ENT_QUOTES, 'UTF-8');
                    } elseif (!empty($firstname)) {
                        $nomComplet = htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8');
                    } elseif (!empty($lastname)) {
                        $nomComplet = htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8');
                    } else {
                        $nomComplet = 'Mon Profil'; // Fallback si aucune information n'est disponible
                    }

                    // Menu pour les utilisateurs CONNECTÉS avec nom personnalisé
                    echo '
                        <!-- MENU PROFIL AVEC SOUS-MENU COMPLET -->
                        <li class="dropdown profil-dropdown">
                            <label for="dropdown-menu" class="dropdown-toggle">👤 ' . $nomComplet . '</label>
                            <input type="checkbox" id="dropdown-menu" class="dropdown-checkbox" />
                            <div class="dropdown-menu profil-submenu">
                                <a href="/mes-favoris" class="submenu-link">
                                    <span class="submenu-icon">❤️</span>
                                    <span class="submenu-text">Mes Favoris</span>
                                </a>
                                <a href="/mes-recettes" class="submenu-link">
                                    <span class="submenu-icon">📓</span>
                                    <span class="submenu-text">Mes Recettes</span>
                                </a>
                                <a href="/ajouter-recette" class="submenu-link">
                                    <span class="submenu-icon">➕</span>
                                    <span class="submenu-text">Ajouter une Recette</span>
                                </a>
                                <a href="/profil" class="submenu-link">
                                    <span class="submenu-icon">⚙️</span>
                                    <span class="submenu-text">Paramètres</span>
                                </a>
                                <div class="dropdown-separator"></div>
                                <a href="/deconnexion" class="submenu-link submenu-logout">
                                    <span class="submenu-icon">🚪</span>
                                    <span class="submenu-text">Déconnexion</span>
                                </a>
                            </div>
                        </li>';
                } else {
                    // Menu pour les utilisateurs NON CONNECTÉS
                    // Seul le lien de connexion est affiché
                    echo '<li class="li red-btn"><a href="/connexion" class="connexion-btn">Connexion</a></li>';
                }
                ?>
            </ul>
        </div>

        <!-- Overlay pour fermer le menu en cliquant à côté (mobile) -->
        <div class="menu-overlay"></div>

    </div>
</header>