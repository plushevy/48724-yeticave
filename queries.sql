INSERT INTO categories (name)
VALUES
  ('Доски и лыжи'),
  ('Крепления'),
  ('Ботинки'),
  ('Одежда'),
  ('Инструменты'),
  ('Разное');


INSERT INTO users (email, name, password, contacts)
VALUES
  ('plue@ya.ru', 'Вася', '12345', '89565441112'),
  ('sdd@ya.ru', 'Олег', '123456', '89564545455'),
  ('zlo@bk.ru', 'Степан', 'qwerty', '89245468877');


INSERT INTO lots (dt_add, dt_end, label, description, img_url, start_price, bet_step, id_user, id_category)
VALUES
  ( '2019-01-15 08:36:32', '2019-02-12 23:59:59', '2014 Rossignol District Snowboard', 'Описание1', 'img/lot-1.jpg', 10999, 100, 1, 1),
  ( '2019-01-23 14:50:59', '2019-03-12 23:59:59', 'DC Ply Mens 2016/2017 Snowboard', 'Описание2', 'img/lot-2.jpg', 159999, 150, 2, 1),
  ( '2019-01-22 10:30:59', '2019-03-13 23:59:59', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Описание3', 'img/lot-3.jpg', 8000, 150, 3, 2),
  ( '2019-01-25 17:00:59', '2019-03-14 23:59:59', 'Ботинки для сноуборда DC Mutiny Charocal', 'Описание4', 'img/lot-4.jpg', 10999, 150, 1, 3),
  ( '2019-01-25 08:20:59', '2019-03-15 23:59:59', 'Куртка для сноуборда DC Mutiny Charocal', 'Описание5', 'img/lot-5.jpg', 7500, 100, 2, 4),
  ( '2019-01-24 14:10:59', '2019-03-16 23:59:59', 'Маска Oakley Canopy', 'Описание6', 'img/lot-6.jpg', 5400, 300, 3, 6);


INSERT INTO bets (dt_create, rate, id_user, id_lot)
VALUES
  ('2019-01-27 14:54:59', 11500, 1, 1 ),
  ('2019-01-28 14:54:59', 11000, 1, 2 ),
  ('2019-02-01 14:54:59', 10500, 1, 3 ),
  ('2019-02-05 14:54:59', 18300, 2, 1 ),
  ('2019-02-08 14:54:59', 12500, 2, 2 ),
  ('2019-02-10 14:54:59', 15500, 3, 4 ),
  ('2019-01-14 14:54:59', 19000, 3, 6 );


-- получить все категории;
SELECT name FROM categories;


-- получить самые новые, открытые лоты. Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT  label, start_price, img_url, max(b.rate) + start_price as last_price , c.name
FROM lots l
JOIN bets b ON b.id_lot = l.id
JOIN categories c ON c.id = l.id_category
WHERE dt_end > CURDATE()
GROUP BY label
ORDER BY dt_add DESC;


-- показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT name, description, start_price, c.name as category
FROM lots l
JOIN categories c ON c.id = l.id_category
WHERE l.id = 2;


-- обновить название лота по его идентификатору;
UPDATE lots SET label = 'Новое название' WHERE id = 3;


-- получить список самых свежих ставок для лота по его идентификатору (дата ставки, ставка, кто поставил)
SELECT dt_create, rate, u.name
FROM bets b
JOIN lots l ON b.id_lot = l.id
JOIN users u ON b.id_user = u.id
WHERE l.id = 2
ORDER BY dt_create DESC
LIMIT 5;

--найти лот по его названию или описанию;
SELECT * FROM lots l WHERE l.label LIKE 'бот%' OR l.description LIKE 'бот%';
