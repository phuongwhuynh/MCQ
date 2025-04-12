DROP DATABASE IF EXISTS mcq_db;
CREATE DATABASE mcq_db;
USE mcq_db;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
	user_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password_hash CHAR(255) NOT NULL,
	email VARCHAR(255),
    name VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    role ENUM('admin','user')
);

DROP TABLE IF EXISTS admin;
CREATE TABLE admin (
	user_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    status ENUM('active','deleted') NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(user_id)
);

DROP TABLE IF EXISTS user;
CREATE TABLE user (
	user_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
	status ENUM('active','deleted') NOT NULL,
    FOREIGN KEY(user_id) REFERENCES users(user_id)
);


DROP TABLE IF EXISTS test;
CREATE TABLE test (
	test_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    total_time INT UNSIGNED NOT NULL,
    number_of_questions SMALLINT UNSIGNED,
    creator INT UNSIGNED,
    created_time datetime,
    status ENUM('public','private','deleted') NOT NULL,
	image_path VARCHAR(255),
    FOREIGN KEY (creator) REFERENCES admin(user_id) 
);


DROP TABLE IF EXISTS question;
CREATE TABLE question (
	question_id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    description TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ans1 TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ans2 TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ans3 TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ans4 TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
	correct_answer ENUM('1','2','3','4') NOT NULL,
    image_path VARCHAR(255),
    creator INT UNSIGNED,
    FOREIGN KEY(creator) REFERENCES admin(user_id)
);

DROP TABLE IF EXISTS test_have_question;
CREATE TABLE test_have_question(
	test_id INT UNSIGNED,
    question_id INT UNSIGNED,
    question_number SMALLINT UNSIGNED,
    PRIMARY KEY(test_id,question_id),
    FOREIGN KEY(test_id) REFERENCES test(test_id),
    FOREIGN KEY(question_id) REFERENCES question(question_id)
);

DROP TABLE IF EXISTS category;
CREATE TABLE category (
	cate VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci PRIMARY KEY
);

DROP TABLE IF EXISTS question_category;
CREATE TABLE question_category(
	question_id INT UNSIGNED,
    cate VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY(question_id,cate),
    FOREIGN KEY(question_id) REFERENCES question(question_id),
    FOREIGN KEY(cate) REFERENCES category(cate)
    
);

DROP TABLE IF EXISTS test_attempt;
CREATE TABLE test_attempt(
	attempt_id INT UNSIGNED PRIMARY KEY,
    status ENUM('IN_PROGRESS','FINISHED') NOT NULL,
    start_time DATETIME NOT NULL,
    current_question SMALLINT UNSIGNED,
    score SMALLINT UNSIGNED,
    user_id INT UNSIGNED,
    test_id INT UNSIGNED,
    FOREIGN KEY(user_id) REFERENCES user(user_id),
    FOREIGN KEY(test_id) REFERENCES test(test_id)
);

DROP TABLE IF EXISTS chosen_answer;
CREATE TABLE chosen_answer(
	question_id INT UNSIGNED,
    attempt_id INT UNSIGNED,
    answer ENUM('1','2','3','4'),
    PRIMARY KEY(question_id,attempt_id),
    FOREIGN KEY(question_id) REFERENCES question(question_id),
    FOREIGN KEY(attempt_id) REFERENCES test_attempt(attempt_id)
);