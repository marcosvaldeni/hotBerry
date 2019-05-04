DROP DATABASE if exists hotberry;
CREATE DATABASE hotberry;

USE hotberry;

DROP TABLE if exists keycodes;
CREATE TABLE keycodes(
keycode_key VARCHAR(6) NOT NULL UNIQUE,
keycode_comment VARCHAR(45),
keycode_used BOOLEAN NOT NULL DEFAULT 0,
PRIMARY KEY(keycode_key)
);

DROP TABLE if exists users;
CREATE TABLE users(
user_id INT AUTO_INCREMENT NOT NULL,
user_name VARCHAR(45),
user_email VARCHAR(45) NOT NULL UNIQUE,
user_pass VARCHAR(64) NOT NULL,
user_status BOOLEAN NOT NULL DEFAULT 1,
user_deleted BOOLEAN NOT NULL DEFAULT 0,
PRIMARY KEY(user_id)
);

DROP TABLE if exists relation;
CREATE TABLE relation(
relation_id INT AUTO_INCREMENT NOT NULL,
relation_level INT(1) NOT NULL DEFAULT 2,
keycode_key VARCHAR(6) NOT NULL,
user_id INT NOT NULL,
PRIMARY KEY(relation_id),
CONSTRAINT keycodes FOREIGN KEY(keycode_key) REFERENCES keycodes(keycode_key),
CONSTRAINT users FOREIGN KEY(user_id) REFERENCES users(user_id)
);

DROP TABLE if exists schedules;
CREATE TABLE schedules(
schedule_id INT AUTO_INCREMENT NOT NULL,
schedule_start INT NOT NULL,
schedule_end INT NOT NULL,
relation_id int not null,
PRIMARY KEY(schedule_id),
CONSTRAINT relation FOREIGN KEY(relation_id) REFERENCES relation (relation_id)
);

/* ################################## <DATA> #################################### */

INSERT INTO keycodes (keycode_key) VALUES 
("8AF11A"),("QL7IBV"),("3UMX9P"),("QW158Z"),("7F6QIQ"),("NW5M9C"),("V4M0V6"),("0IB81A"),
("PH8F0O"),("P21B53"),("DB33U6"),("Q2AK4W"),("DBPP6I"),("IBK13I"),("L73TKD"),("2FL19U"),
("SL7J8R"),("3UNRFN"),("4M8OQ0"),("0QMBJQ"),("CTO4I4"),("N13C4L"),("81KMD3"),("00ZVXV"),
("0O1YPB"),("QW324F"),("43L8FN"),("9I2TPU"),("SMDB0Y"),("5JCOO4"),("Z74G1X");

/* ################################## </DATA> #################################### */


DELIMITER //
DROP PROCEDURE IF EXISTS createUser//
CREATE PROCEDURE  createUser(IN email VARCHAR(45), IN pass VARCHAR(64), IN keycode VARCHAR(6))
BEGIN
    DECLARE id INT;
	
    START TRANSACTION; 
          
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


DELIMITER //
DROP PROCEDURE IF EXISTS createSchedule//
CREATE PROCEDURE  createSchedule(IN userId INT, IN keycode VARCHAR(6), IN startSchedule INT, IN endSchedule INT)
BEGIN

	DECLARE ralationId INT;

	SELECT relation_id FROM relation WHERE keycode_key = keycode AND user_id = userId INTO ralationId;
    
	INSERT INTO schedules (schedule_start, schedule_end, relation_id) VALUES
    (startSchedule, endSchedule, ralationId);
       
END //
DELIMITER ;


DELIMITER //
DROP FUNCTION IF EXISTS checkEmailAndKey//
CREATE FUNCTION checkEmailAndKey(email VARCHAR(45), keycode VARCHAR(7)) RETURNS VARCHAR(2) 
BEGIN
	DECLARE cheking BOOLEAN;
	
	IF (SELECT COUNT(*) FROM users WHERE user_email = email) != 0 THEN
    		# Email already registered! #
	   RETURN "4";
	ELSEIF (SELECT COUNT(*) FROM keycodes WHERE keycode_key = keycode) != 1 THEN
			# Key is invalid! Key does not exist #
	   RETURN "7";
	ELSEIF (SELECT keycode_used FROM keycodes WHERE keycode_key = keycode) = 1 THEN
			# Key is invalid! Already used #
	   RETURN "7";
	ELSE
			# Valid key! #
	   RETURN "ok";
	END IF;

END//
DELIMITER ;

/* ################################## <SIMULATION DATA> #################################### */
CALL createAdmin("8AF11A","caroldanvers@email.comm","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", @resutl);
CALL createUser("davidbonner@email.com", "$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", "8AF11A");
CALL createUser("hankpym@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", "8AF11A");
CALL createUser("emmafrost@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", "8AF11A");

CALL createAdmin("SMDB0Y","stevenrogers@email.comm","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", @resutl);
INSERT INTO relation (relation_level, keycode_key, user_id) VALUES (1, "5JCOO4",5);
CALL createUser("annamarie@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","5JCOO4");
CALL createUser("mollyfitzgerald@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","5JCOO4");
CALL createUser("williamnasland@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","5JCOO4");

CALL createAdmin("00ZVXV","joshuasanders@email.comm","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa", @resutl);
CALL createUser("jaygarrick@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","00ZVXV");
CALL createUser("troystewart@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","00ZVXV");
CALL createUser("danieleaton@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","00ZVXV");
CALL createUser("samanthaparrington@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","00ZVXV");
CALL createUser("guygardner@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","00ZVXV");
CALL createUser("cassiesandsmark@email.com","$2y$10$Gr15GhasjDto5Nt8Bz5N3e5dhN/epsID4/eDrkyJQUU5pJjT8NIAa","00ZVXV");
               
/* ################################## </SIMULATION DATA> #################################### */


# <temp>
SELECT * from keycodes;

# </temp>
