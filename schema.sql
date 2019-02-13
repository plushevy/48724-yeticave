
CREATE DATABASE yeticave_db
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE yeticave_db;


CREATE TABLE categories(
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(32) UNIQUE
);


CREATE TABLE users(
  id INT PRIMARY KEY AUTO_INCREMENT,
  dt_registration TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(128) UNIQUE NOT NULL,
  password VARCHAR(32),
  img_url VARCHAR(128) DEFAULT 'img/user.jpg',
  contacts VARCHAR(128),
  is_winner BOOLEAN DEFAULT FALSE
);

CREATE TABLE lots(
  id INT PRIMARY KEY AUTO_INCREMENT,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dt_end TIMESTAMP,
  label VARCHAR(128),
  description TEXT,
  img_url VARCHAR(128),
  start_price INT,
  bet_step INT,
  id_user INT,
  id_winner INT,
  id_category INT
);


CREATE TABLE bets(
  id INT PRIMARY KEY AUTO_INCREMENT,
  dt_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  rate INT,
  id_user INT,
  id_lot INT
);

CREATE INDEX l_category_id_index ON lots(id_category);
CREATE INDEX b_rate_id_index ON bets(rate);


-- FOREING KEYS
ALTER TABLE bets
  ADD CONSTRAINT bets_user_id__fk
FOREIGN KEY (id_user) REFERENCES users (id);

ALTER TABLE bets
  ADD CONSTRAINT bets_lot_id__fk
FOREIGN KEY (id_lot) REFERENCES lots (id);

ALTER TABLE lots
  ADD CONSTRAINT lots_user_id__fk
FOREIGN KEY (id_user) REFERENCES users (id);

ALTER TABLE lots
  ADD CONSTRAINT lots_winner_id__fk
FOREIGN KEY (id_winner) REFERENCES users (id);

ALTER TABLE lots
  ADD CONSTRAINT lots_category_id__fk
FOREIGN KEY (id_category) REFERENCES categories (id);
