DROP DATABASE IF EXISTS yeticave_db;

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
  name VARCHAR(64) NOT NULL,
  password VARCHAR(255) NOT NULL,
  img_url VARCHAR(128),
  contacts VARCHAR(128)
);

CREATE TABLE lots(
  id INT PRIMARY KEY AUTO_INCREMENT,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dt_end TIMESTAMP,
  label VARCHAR(128) NOT NULL,
  description TEXT,
  img_url VARCHAR(128),
  start_price INT,
  bet_step INT,
  id_user INT,
  id_winner INT,
  id_category INT,
  FULLTEXT (`label`, `description`)
);


CREATE TABLE bets(
  id INT PRIMARY KEY AUTO_INCREMENT,
  dt_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_price INT,
  id_user INT,
  id_lot INT
);


CREATE INDEX bets_id_lot_last_price ON bets(id_lot, last_price);
CREATE INDEX l_dt_add_id_index ON lots(dt_add);
CREATE INDEX l_dt_end_id_index ON lots(dt_end);


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
  name VARCHAR(64) NOT NULL,
  password VARCHAR(255) NOT NULL,
  img_url VARCHAR(128),
  contacts VARCHAR(128)
);

CREATE TABLE lots(
  id INT PRIMARY KEY AUTO_INCREMENT,
  dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dt_end TIMESTAMP,
  label VARCHAR(128) NOT NULL,
  description TEXT,
  img_url VARCHAR(128),
  start_price INT,
  bet_step INT,
  id_user INT,
  id_winner INT,
  id_category INT,
  FULLTEXT (`label`, `description`)
);


CREATE TABLE bets(
  id INT PRIMARY KEY AUTO_INCREMENT,
  dt_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  last_price INT,
  id_user INT,
  id_lot INT
);


CREATE INDEX bets_id_lot_last_price ON bets(id_lot, last_price);
CREATE INDEX l_dt_add_id_index ON lots(dt_add);
CREATE INDEX l_dt_end_id_index ON lots(dt_end);


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
