CREATE TABLE users
(
 user_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(user_id),
 username VARCHAR(30),
 password_hash CHAR(40),
	auth_code VARCHAR(20)
);

CREATE TABLE characters
(
 character_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(character_id),
 character_name VARCHAR(50),
	character_level INT,
	str_attr INT,
	int_attr INT,
	char_attr INT,
	con_attr INT,
	dex_attr INT,
	wis_attr INT,
	weight INT,
	height INT,
	age INT,
	religion VARCHAR(20),
	gender VARCHAR(10),
	char_class INT,
	FOREIGN KEY (char_class) REFERENCES classes(class_id),
	race INT,
	FOREIGN KEY (race) REFERENCES races(race_id),
	hit_points INT,
	alignment CHAR(2),
	money DECIMAL(10, 2),
	user_id INT,
	FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE permissions
(
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(user_id),
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	CONSTRAINT permissions_id PRIMARY KEY (user_id, character_id)
);

CREATE TABLE skills
(
	skill_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(skill_id),
	skill_name VARCHAR(20),
	attribute CHAR(3),
	armor_penalty INT,
	trained BOOLEAN
	# synergies
);

CREATE TABLE races
(
	race_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(race_id),
	race_name VARCHAR(20),
	speed INT,
	race_size INT,
	racial_features TEXT,
	ability_adjustments TEXT
);

CREATE TABLE classes
(
	class_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(class_id),
	class_name VARCHAR(20),
	base_attack INT,
	fort_save INT,
	will_save INT,
	ref_save INT,
	hd INT,
	spells_per_day INT,
	class_features TEXT,
	alignment TEXT,
	skill_points INT
);

CREATE TABLE feats
(
	feat_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(feat_id),
	feat_name VARCHAR(20),
	description TEXT,
	prerequisites TEXT
);

CREATE TABLE languages
(
	language_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(language_id),
	language_name VARCHAR(20)
);

CREATE TABLE spells
(
	spell_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(spell_id),
	spell_name VARCHAR(20),
	spell_type VARCHAR(20)
);

CREATE TABLE armor
(
	armor_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(armor_id),
	armor_name VARCHAR(20),
	cost DECIMAL(10, 2),
	armor_bonus INT,
	max_dex INT,
	armor_check_penalty INT,
	arcane_spell_failure_chance INT,
	weight INT
);

CREATE TABLE generic_items
(
	generic_item_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(generic_item_id),
	generic_item_name VARCHAR(30),
	cost DECIMAL(10, 2),
	weight INT
);

CREATE TABLE weapons
(
	weapon_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(weapon_id),
	weapons_name VARCHAR(30),
	damage VARCHAR(10),
	weapon_range INT,
	cost DECIMAL(10, 2),
	weight INT,
	critical VARCHAR(10),
	damage_type VARCHAR(3)
);

CREATE TABLE characters_skills
(
	character_id INT,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	skill_id INT,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	skill_rank INT
);

CREATE TABLE skills_races
(
	skill_id INT,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	race_id INT,
	FOREIGN KEY (race_id) REFERENCES races(race_id)
);

CREATE TABLE skills_classes
(
	skill_id INT,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	class_id INT,
	FOREIGN KEY (class_id) REFERENCES classes(class_id),
	cross_class BOOLEAN
);

CREATE TABLE characters_feats
(
	character_id INT,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	feat_id INT,
	FOREIGN KEY (feat_id) REFERENCES feats(feat_id)
);

CREATE TABLE characters_languages
(
	character_id INT,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	language_id INT,
	FOREIGN KEY (language_id) REFERENCES languages(language_id)
);

CREATE TABLE characters_spells
(
	character_id INT,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	spell_id INT,
	FOREIGN KEY (spell_id) REFERENCES spells(spell_id)
);

CREATE TABLE characters_armors
(
	character_id INT,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	armor_id INT,
	FOREIGN KEY (armor_id) REFERENCES armors(armor_id),
	quantity INT,
	location VARCHAR(30)
);

CREATE TABLE characters_generic_items
(
	character_id INT,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	generic_item_id INT,
	FOREIGN KEY (generic_item_id) REFERENCES generic_items(generic_item_id),
	quantity INT,
	location VARCHAR(30)
);

CREATE TABLE characters_weapons
(
	character_id INT,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	weapon_id INT,
	FOREIGN KEY (weapon_id) REFERENCES weapons(weapon_id),
	quantity INT,
	location VARCHAR(30)
);