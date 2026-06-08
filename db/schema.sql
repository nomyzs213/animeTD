CREATE TABLE users (
    user_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(160) NOT NULL UNIQUE,
    email VARCHAR(160) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE highscores (
    score_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    user_id INTEGER NOT NULL,
    yens DECIMAL(24, 2) NOT NULL,
    upgrade_level_1 INTEGER NOT NULL DEFAULT 0,
    upgrade_level_2 INTEGER NOT NULL DEFAULT 0,
    upgrade_level_3 INTEGER NOT NULL DEFAULT 0,
    upgrade_level_4 INTEGER NOT NULL DEFAULT 0,
    upgrade_level_5 INTEGER NOT NULL DEFAULT 0,
    upgrade_level_6 INTEGER NOT NULL DEFAULT 0,
    upgrade_level_7 INTEGER NOT NULL DEFAULT 0,
    upgrade_level_8 INTEGER NOT NULL DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);  

