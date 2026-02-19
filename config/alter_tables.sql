ALTER TABLE menus
ADD regime ENUM('classique', 'vegetarien', 'vegan') NOT NULL DEFAULT 'classique';
