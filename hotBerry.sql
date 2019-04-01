DROP DATABASE hotBerry;

CREATE DATABASE hotBerry;

USE hotBerry;

DROP TABLE if exists keycodes;
CREATE TABLE keycodes(
keycode_key VARCHAR(12) NOT NULL UNIQUE,
keycode_used BOOLEAN NOT NULL DEFAULT 0,
PRIMARY KEY(keycode_key)
);

DROP TABLE if exists users;
CREATE TABLE users(
user_id INT AUTO_INCREMENT NOT NULL,
user_name VARCHAR(45),
user_email VARCHAR(45) NOT NULL UNIQUE,
user_pass VARCHAR(65) NOT NULL,
user_status BOOLEAN NOT NULL DEFAULT 0,
PRIMARY KEY(user_id)
);

DROP TABLE if exists schedules;
CREATE TABLE schedules(
schedule_id INT AUTO_INCREMENT NOT NULL,
schedule_start INT(24) NOT NULL,
schedule_end INT(24) NOT NULL,
user_id INT NOT NULL,
keycode_key VARCHAR(12) NOT NULL,
PRIMARY KEY(schedule_id)
);

DROP TABLE if exists relation;
CREATE TABLE relation(
relation_id INT AUTO_INCREMENT NOT NULL,
relation_level INT(1) NOT NULL DEFAULT 2,
relation_comment VARCHAR(45),
keycode_key VARCHAR(12) NOT NULL,
user_id INT NOT NULL,
PRIMARY KEY(relation_id),
CONSTRAINT keycodes FOREIGN KEY(keycode_key) REFERENCES keycodes(keycode_key),
CONSTRAINT users FOREIGN KEY(user_id) REFERENCES users(user_id)
);

/* ################################## <DATA> #################################### */

INSERT INTO keycodes (keycode_key) VALUES 
("8AF11A"),("QL7IBV"),("3UMX9P"),("QW158Z"),("7F6QIQ"),("NW5M9C"),("V4M0V6"),("0IB81A"),
("PH8F0O"),("P21B53"),("DB33U6"),("Q2AK4W"),("DBPP6I"),("IBK13I"),("L73TKD"),("2FL19U"),
("SL7J8R"),("3UNRFN"),("4M8OQ0"),("0QMBJQ"),("CTO4I4"),("N13C4L"),("81KMD3"),("00ZVXV"),
("0O1YPB"),("QW324F"),("43L8FN"),("9I2TPU"),("SMDB0Y"),("5JCOO4"),("Z74G1X");

/* ################################## <DATA/> #################################### */

DELIMITER //
DROP PROCEDURE IF EXISTS createUser//
CREATE PROCEDURE  createUser(IN admin_id INT, IN email VARCHAR(45), IN pass VARCHAR(65))
BEGIN
	DECLARE keycode VARCHAR(6);
    DECLARE id INT;
	
    START TRANSACTION; 
    
		SELECT keycode_key FROM users INNER JOIN  relation ON relation.user_id = users.user_id
		WHERE users.user_id = admin_id INTO keycode;
        
	   	INSERT INTO users (user_email, user_pass) VALUES 
		(email, pass);
        
        SELECT user_id FROM users WHERE user_email = email into id;

		INSERT INTO relation (keycode_key, user_id) VALUES 
		(keycode, id);
        
	COMMIT;
        
END //
DELIMITER ;

DELIMITER //
DROP PROCEDURE IF EXISTS createAdmin//
CREATE PROCEDURE  createAdmin(IN keycode VARCHAR(12), IN email VARCHAR(45), IN pass VARCHAR(65), OUT result BOOLEAN)
BEGIN

	START TRANSACTION; 
	IF (SELECT keycode_used FROM keycodes WHERE keycode_key = keycode) != 1 THEN
    
	   	INSERT INTO users (user_email, user_pass, user_status) VALUES 
		(email, pass, 1);

		INSERT INTO relation (relation_level, keycode_key, user_id) VALUES 
		(1, keycode, (SELECT user_id FROM users WHERE user_email = email));
    
		UPDATE keycodes SET keycode_used = 1 
		WHERE keycode_key = keycode;
        
	ELSE
    
	   SELECT keycode_used FROM keycodes WHERE keycode_key = keycode INTO result;
       
	END IF;
	COMMIT;
        
END //
DELIMITER ;

select * from users;

select * from relation;

#/*
CALL createAdmin("8AF11A","caroldanvers@email.comm","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", @resutl);
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"8AF11A"
                and relation.relation_level = 1), "davidbonner@email.com", "$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"8AF11A"
                and relation.relation_level = 1), "hankpym@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"8AF11A"
                and relation.relation_level = 1), "emmafrost@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");

CALL createAdmin("SMDB0Y","stevenrogers@email.comm","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", @resutl);
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"SMDB0Y"
                and relation.relation_level = 1),"annamarie@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"SMDB0Y"
                and relation.relation_level = 1),"mollyfitzgerald@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"SMDB0Y"
                and relation.relation_level = 1),"williamnasland@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");

CALL createAdmin("00ZVXV","joshuasanders@email.comm","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", @resutl);
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"00ZVXV"
                and relation.relation_level = 1),"jaygarrick@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"00ZVXV"
                and relation.relation_level = 1),"troystewart@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"00ZVXV"
                and relation.relation_level = 1),"danieleaton@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"00ZVXV"
                and relation.relation_level = 1),"samanthaparrington@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"00ZVXV"
                and relation.relation_level = 1),"guygardner@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
CALL createUser((select relation.user_id from relation
				INNER JOIN  users ON users.user_id = relation.user_id WHERE relation.keycode_key = 
				"00ZVXV"
                and relation.relation_level = 1),"cassiesandsmark@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa");
#*/

# Select para mostrar os usuarios, dando a opcao para o admin add um deles #########
SELECT keycode_key, user_email FROM users 
INNER JOIN  relation ON relation.user_id = users.user_id
WHERE keycode_key = "00ZVXV" and users.user_id != 1;
############

SELECT * FROM users 
INNER JOIN  relation ON relation.user_id = users.user_id
WHERE keycode_key = "8AF11A" and users.user_id != 1;

DELETE FROM users WHERE user_id = 2;

select relation.user_id from relation
INNER JOIN  users ON users.user_id = relation.user_id
WHERE relation.keycode_key = "8AF11A" and relation.relation_level = 1;


