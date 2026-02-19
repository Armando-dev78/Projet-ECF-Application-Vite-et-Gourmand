-- Comptes internes
INSERT INTO users (nom, prenom, email, password, role) VALUES
('Gomez', 'José', 'jose@vitegourmand.fr', '$2y$10$hashadmin', 'admin'),
('Martin', 'Julie', 'julie@vitegourmand.fr', '$2y$10$hashemploye', 'employe');

-- Menus événementiels
INSERT INTO menus (nom, description, theme, nb_personnes_min, prix_par_personne, delai_commande_jours, stock_disponible) VALUES
('Menu Mariage', 'Menu gastronomique pour réceptions de mariage', 'mariage', 50, 65.00, 30, 5),
('Menu Noël', 'Menu festif spécial fêtes de fin d’année', 'noel', 20, 45.00, 14, 10),
('Menu Pâques', 'Menu printanier pour célébrer Pâques', 'paques', 15, 38.00, 10, 8),
('Cocktail Entreprise', 'Formule cocktail pour événements professionnels', 'entreprise', 10, 28.00, 7, 12),
('Carte Boissons', 'Sélection de boissons pour accompagner vos événements', 'boisson', 1, 0.00, 2, 50);

-- Plats disponibles
INSERT INTO plats (nom, type, prix) VALUES
('Foie gras maison', 'entree', 12.00),
('Saumon fumé', 'entree', 10.00),
('Velouté de saison', 'entree', 7.00),
('Filet de bœuf sauce forestière', 'plat', 25.00),
('Suprême de volaille', 'plat', 18.00),
('Risotto aux champignons', 'plat', 16.00),
('Tarte aux fruits frais', 'dessert', 8.00),
('Fondant au chocolat', 'dessert', 8.00),
('Bûche de Noël', 'dessert', 9.00),
('Eau minérale', 'boisson', 2.00),
('Soda', 'boisson', 3.00),
('Jus de fruits', 'boisson', 3.00),
('Vin (verre)', 'boisson', 5.00),
('Champagne (coupe)', 'boisson', 7.00);

-- Association menus / plats
INSERT INTO menu_plat (menu_id, plat_id) VALUES
(1,1),(1,2),(1,4),(1,7),
(2,2),(2,4),(2,9),
(3,3),(3,6),(3,7),
(4,3),(4,6),
(5,10),(5,11),(5,12),(5,13),(5,14);