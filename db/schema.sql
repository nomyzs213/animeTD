PRAGMA foreign_keys = ON;
CREATE TABLE users (
    user_id INTEGER PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255)NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
);

CREATE TABLE highscores (
    user_id INTEGER NOT NULL,
    higscore INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);  

