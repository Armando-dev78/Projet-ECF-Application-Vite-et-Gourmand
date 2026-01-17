-- TABLE utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('utilisateur', 'employe', 'admin') NOT NULL DEFAULT 'utilisateur',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- TABLE menus
CREATE TABLE menus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT NOT NULL,
    theme VARCHAR(100) NOT NULL,
    nb_personnes_min INT NOT NULL,
    prix_par_personne DECIMAL(8,2) NOT NULL,
    delai_commande_jours INT NOT NULL,
    stock_disponible INT NOT NULL DEFAULT 0
);

-- TABLE plats
CREATE TABLE plats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    type ENUM('entree', 'plat', 'dessert', 'boisson') NOT NULL,
    prix DECIMAL(8,2) NOT NULL
);

-- TABLE allergenes
CREATE TABLE allergenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL
);

-- TABLE menu_plat (relation N:N)
CREATE TABLE menu_plat (
    menu_id INT NOT NULL,
    plat_id INT NOT NULL,
    PRIMARY KEY (menu_id, plat_id),
    FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE,
    FOREIGN KEY (plat_id) REFERENCES plats(id) ON DELETE CASCADE
);

-- TABLE plat_allergene (relation N:N)
CREATE TABLE plat_allergene (
    plat_id INT NOT NULL,
    allergene_id INT NOT NULL,
    PRIMARY KEY (plat_id, allergene_id),
    FOREIGN KEY (plat_id) REFERENCES plats(id) ON DELETE CASCADE,
    FOREIGN KEY (allergene_id) REFERENCES allergenes(id) ON DELETE CASCADE
);

-- TABLE commandes
CREATE TABLE commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    menu_id INT NOT NULL,
    nb_personnes INT NOT NULL,
    total DECIMAL(8,2) NOT NULL,
    reduction DECIMAL(8,2) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (menu_id) REFERENCES menus(id)
);

-- TABLE suivi des commandes
CREATE TABLE suivi_commandes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    statut ENUM(
        'en_attente',
        'acceptee',
        'en_preparation',
        'en_livraison',
        'livree',
        'terminee'
    ) NOT NULL,
    date_statut DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commande_id) REFERENCES commandes(id) ON DELETE CASCADE
);

-- TABLE avis clients
CREATE TABLE avis (
    id INT AUTO_INCREMENT PRIMARY KEY,
    commande_id INT NOT NULL,
    note INT CHECK (note BETWEEN 1 AND 5),
    commentaire TEXT,
    valide BOOLEAN DEFAULT FALSE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (commande_id) REFERENCES commandes(id)
);