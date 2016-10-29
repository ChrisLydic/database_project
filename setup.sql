DROP DATABASE IF EXISTS rpg;

CREATE DATABASE IF NOT EXISTS rpg DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE rpg;

CREATE USER IF NOT EXISTS 'rpg_user'@'localhost' IDENTIFIED BY 'This_is_a_passphrase!';
GRANT ALL PRIVILEGES ON rpg.* TO 'rpg_user'@'localhost'; # TODO Might want to restrict permissions later

CREATE TABLE users
(
	user_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(user_id),
	username VARCHAR(30) NOT NULL,
	UNIQUE (username),
	password_hash CHAR(40) NOT NULL,
	auth_code VARCHAR(20) DEFAULT 'true' # Can be modified to deactivate users
);

# Add your own preferred default user for testing
INSERT INTO users (username, password_hash) VALUES ('user', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8'); # password = 'password'
INSERT INTO users (username, password_hash) VALUES ('alexwho314', '934aae49f648ed870c9c421829f4cece6643cf86'); # password = 'mango'

CREATE TABLE characters
(
	character_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(character_id),
	character_name VARCHAR(50) NOT NULL,
	character_level INT NOT NULL, # include XP too?
	CHECK (character_level > 0),
	str_attr INT, # No NOT NULL because not needed to be a creature (e.g. non-corporeal)
	CHECK (str_attr > 0),
	dex_attr INT,
	CHECK (dex_attr > 0),
	con_attr INT,
	CHECK (con_attr > 0),
	int_attr INT,
	CHECK (int_attr > 0),
	wis_attr INT NOT NULL, # Required in order to be considered a creature
	CHECK (wis_attr > 0),
	cha_attr INT NOT NULL, # Required in order to be considered a creature
	CHECK (cha_attr > 0),
	weight INT, # Non-corporeal can be massless
	CHECK (weight >= 0),
	height INT,
	CHECK (height > 0),
	age INT,
	CHECK (age >= 0),
	religion VARCHAR(20),
	gender VARCHAR(10),
	char_class INT NOT NULL,
	FOREIGN KEY (char_class) REFERENCES classes(class_id),
	race INT NOT NULL,
	FOREIGN KEY (race) REFERENCES races(race_id),
	hit_points INT NOT NULL,
	alignment CHAR(2) NOT NULL,
	CHECK (alignment IN ('LG', 'NG', 'CG', 'LN', 'N', 'CN', 'LE', 'NE', 'CE')),
	money DECIMAL(10, 2) NOT NULL DEFAULT 0,
	CHECK (money >= 0),
	user_id INT NOT NULL,
	FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE skills
(
	skill_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(skill_id),
	skill_name VARCHAR(40) NOT NULL,
	attribute CHAR(3) NOT NULL,
	CHECK (attribute IN ('STR', 'DEX', 'CON', 'INT', 'WIS', 'CHA', 'NON')),
	armor_penalty INT NOT NULL,
	CHECK (armor_penalty >= 0),
	untrained BOOLEAN NOT NULL
	# synergies
);

INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Appraise', 'INT', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Balance', 'DEX', TRUE, 1);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Bluff', 'CHA', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Climb', 'STR', TRUE, 1);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Concentration', 'CON', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Craft', 'INT', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Decipher Script', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Diplomacy', 'CHA', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Disable Device', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Disguise', 'CHA', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Escape Artist', 'DEX', TRUE, 1);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Forgery', 'INT', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Gather Information', 'CHA', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Handle Animal', 'CHA', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Heal', 'WIS', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Hide', 'DEX', TRUE, 1);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Intimidate', 'CHA', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Jump', 'STR', TRUE, 1);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (arcana)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (architecture and engineering)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (dungeoneering)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (geography)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (history)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (local)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (nature)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (nobility and royalty)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (religion)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Knowledge (the planes)', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Listen', 'WIS', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Move Silently', 'DEX', TRUE, 1);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Open Lock', 'DEX', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Perform', 'CHA', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Profession', 'WIS', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Ride', 'DEX', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Search', 'INT', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Sense Motive', 'WIS', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Sleight of Hand', 'DEX', FALSE, 1);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Speak Language', 'NON', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Spellcraft', 'INT', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Spot', 'WIS', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Survival', 'WIS', TRUE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Swim', 'STR', TRUE, 2);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Tumble', 'DEX', FALSE, 1);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Use Magic Device', 'CHA', FALSE, 0);
INSERT INTO skills (skill_name, attribute, untrained, armor_penalty) VALUES ('Use Rope', 'DEX', TRUE, 0);

CREATE TABLE races
(
	race_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(race_id),
	race_name VARCHAR(20) NOT NULL,
	UNIQUE (race_name),
	speed INT NOT NULL,
	CHECK (speed >= 0),
	race_size VARCHAR(10) NOT NULL,
	CHECK (race_size IN ('Fine', 'Diminutive', 'Tiny', 'Small', 'Medium', 'Large', 'Huge', 'Gargantuan', 'Colossal')),
	ability_adjustments TEXT NOT NULL,
	racial_features TEXT
);

INSERT INTO races (race_name, speed, race_size, ability_adjustments) VALUES ('Human', 30, 'Medium', 'None');
INSERT INTO races (race_name, speed, race_size, ability_adjustments) VALUES ('Dwarf', 20, 'Medium', '+2 Constitution, –2 Charisma');
INSERT INTO races (race_name, speed, race_size, ability_adjustments) VALUES ('Elf', 30, 'Medium', '+2 Dexterity, –2 Constitution');
INSERT INTO races (race_name, speed, race_size, ability_adjustments) VALUES ('Gnome', 20, 'Small', '+2 Constitution, –2 Strength');
INSERT INTO races (race_name, speed, race_size, ability_adjustments) VALUES ('Half-elf', 30, 'Medium', 'None');
INSERT INTO races (race_name, speed, race_size, ability_adjustments) VALUES ('Half-orc', 30, 'Medium', '+2 Strength, –2 Intelligence, –2 Charisma');
INSERT INTO races (race_name, speed, race_size, ability_adjustments) VALUES ('Halfling', 20, 'Small', '+2 Dexterity, –2 Strength');

CREATE TABLE classes
(
	class_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(class_id),
	class_name VARCHAR(20) NOT NULL,
	UNIQUE (class_name),
	base_attack VARCHAR(7) NOT NULL,
	CHECK (base_attack IN ('Good', 'Average', 'Poor')),
	fort_save VARCHAR(4) NOT NULL,
	CHECK (fort_save IN ('Good', 'Poor')),
	ref_save VARCHAR(4) NOT NULL,
	CHECK (ref_save IN ('Good', 'Poor')),
	will_save VARCHAR(4) NOT NULL,
	CHECK (will_save IN ('Good', 'Poor')),
	hd INT NOT NULL,
	skill_points INT NOT NULL,
	alignment TEXT NOT NULL,
	class_features TEXT
);

INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Barbarian', 'Good', 'Good', 'Poor', 'Poor', 12, 4, '!L');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Bard', 'Average', 'Poor', 'Good', 'Good', 6, 6, '!L');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Cleric', 'Average', 'Good', 'Poor', 'Good', 8, 2, 'Within one step of deity');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Druid', 'Average', 'Good', 'Poor', 'Good', 8, 4, 'Any N');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Fighter', 'Good', 'Good', 'Poor', 'Poor', 10, 2, 'Any');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Monk', 'Average', 'Good', 'Good', 'Good', 8, 4, 'Any L');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Paladin', 'Good', 'Good', 'Poor', 'Poor', 10, 2, 'LG');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Ranger', 'Good', 'Good', 'Good', 'Poor', 8, 6, 'Any');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Rogue', 'Average', 'Poor', 'Good', 'Poor', 6, 8, 'Any');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Sorcerer', 'Poor', 'Poor', 'Poor', 'Good', 4, 2, 'Any');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Wizard', 'Poor', 'Poor', 'Poor', 'Good', 4, 2, 'Any');

CREATE TABLE feats
(
	feat_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(feat_id),
	feat_name VARCHAR(20) NOT NULL,
	UNIQUE (feat_name),
	description TEXT,
	prerequisites TEXT
);

CREATE TABLE languages
(
	language_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(language_id),
	language_name VARCHAR(20) NOT NULL,
	UNIQUE (language_name),
	alphabet VARCHAR(10) NOT NULL
);

INSERT INTO languages(language_name, alphabet) VALUES ('Abyssal', 'Infernal');
INSERT INTO languages(language_name, alphabet) VALUES ('Aquan', 'Elven');
INSERT INTO languages(language_name, alphabet) VALUES ('Auran', 'Draconic');
INSERT INTO languages(language_name, alphabet) VALUES ('Celestial', 'Celestial');
INSERT INTO languages(language_name, alphabet) VALUES ('Common', 'Common');
INSERT INTO languages(language_name, alphabet) VALUES ('Draconic', 'Draconic');
INSERT INTO languages(language_name, alphabet) VALUES ('Druidic', 'Druidic');
INSERT INTO languages(language_name, alphabet) VALUES ('Dwarven', 'Dwarven');
INSERT INTO languages(language_name, alphabet) VALUES ('Elven', 'Elven');
INSERT INTO languages(language_name, alphabet) VALUES ('Giant', 'Dwarven');
INSERT INTO languages(language_name, alphabet) VALUES ('Gnome', 'Dwarven');
INSERT INTO languages(language_name, alphabet) VALUES ('Goblin', 'Dwarven');
INSERT INTO languages(language_name, alphabet) VALUES ('Gnoll', 'Common');
INSERT INTO languages(language_name, alphabet) VALUES ('Halfling', 'Common');
INSERT INTO languages(language_name, alphabet) VALUES ('Ignan', 'Draconic');
INSERT INTO languages(language_name, alphabet) VALUES ('Infernal', 'Infernal');
INSERT INTO languages(language_name, alphabet) VALUES ('Orc', 'Dwarven');
INSERT INTO languages(language_name, alphabet) VALUES ('Sylvan', 'Elven');
INSERT INTO languages(language_name, alphabet) VALUES ('Terran', 'Dwarven');
INSERT INTO languages(language_name, alphabet) VALUES ('Undercommon', 'Elven');

CREATE TABLE spells
(
	spell_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(spell_id),
	spell_name VARCHAR(20) NOT NULL,
	UNIQUE (spell_name),
	spell_type VARCHAR(20) NOT NULL
);

CREATE TABLE armor
(
	armor_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(armor_id),
	armor_name VARCHAR(20) NOT NULL,
	UNIQUE (armor_name),
	cost DECIMAL(10, 2) NOT NULL,
	armor_bonus INT NOT NULL,
	max_dex INT NOT NULL,
	armor_check_penalty INT NOT NULL,
	arcane_spell_failure_chance INT NOT NULL,
	weight INT
);

CREATE TABLE generic_items
(
	generic_item_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(generic_item_id),
	generic_item_name VARCHAR(30) NOT NULL,
	UNIQUE (generic_item_name),
	cost DECIMAL(10, 2) NOT NULL,
	weight INT
);

CREATE TABLE weapons
(
	weapon_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(weapon_id),
	weapon_name VARCHAR(30) NOT NULL,
	UNIQUE (weapon_name),
	damage VARCHAR(10) NOT NULL,
	weapon_range INT,
	cost DECIMAL(10, 2) NOT NULL,
	weight INT,
	critical VARCHAR(10) NOT NULL,
	damage_type VARCHAR(3) NOT NULL
);

CREATE TABLE characters_skills  # TODO Add some sort of on-delete cascade; aso add official primary keys
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	skill_id INT NOT NULL,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	skill_rank INT NOT NULL,
	CHECK (bonus >= 0),
	PRIMARY KEY (character_id, skill_id)
);

CREATE TABLE skills_races
(
	skill_id INT NOT NULL,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	race_id INT NOT NULL,
	FOREIGN KEY (race_id) REFERENCES races(race_id),
	bonus INT NOT NULL,
	PRIMARY KEY (skill_id, race_id)
);

CREATE TABLE skills_classes
(
	skill_id INT NOT NULL,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	class_id INT NOT NULL,
	FOREIGN KEY (class_id) REFERENCES classes(class_id),
	cross_class BOOLEAN NOT NULL,
	PRIMARY KEY (skill_id, class_id)
);

CREATE TABLE characters_feats
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	feat_id INT NOT NULL,
	FOREIGN KEY (feat_id) REFERENCES feats(feat_id),
	PRIMARY KEY (character_id, feat_id)
);

CREATE TABLE characters_languages
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	language_id INT NOT NULL,
	FOREIGN KEY (language_id) REFERENCES languages(language_id),
	PRIMARY KEY (character_id, language_id)
);

CREATE TABLE characters_spells
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	spell_id INT NOT NULL,
	FOREIGN KEY (spell_id) REFERENCES spells(spell_id),
	PRIMARY KEY (character_id, spell_id)
);

CREATE TABLE characters_armors
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	armor_id INT NOT NULL,
	FOREIGN KEY (armor_id) REFERENCES armors(armor_id),
	quantity INT NOT NULL,
	location VARCHAR(30),
	PRIMARY KEY (character_id, armor_id)
);

CREATE TABLE characters_generic_items
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	generic_item_id INT NOT NULL,
	FOREIGN KEY (generic_item_id) REFERENCES generic_items(generic_item_id),
	quantity INT NOT NULL,
	location VARCHAR(30),
	PRIMARY KEY (character_id, generic_item_id)
);

CREATE TABLE characters_weapons
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id),
	weapon_id INT NOT NULL,
	FOREIGN KEY (weapon_id) REFERENCES weapons(weapon_id),
	quantity INT NOT NULL,
	location VARCHAR(30),
	PRIMARY KEY (character_id, weapon_id)
);

INSERT INTO characters (character_id, character_name, character_level, str_attr, dex_attr, con_attr, int_attr, wis_attr, cha_attr, weight, height, age, religion, gender, char_class, race, hit_points, alignment, money, user_id) VALUES (1, 'Akane', 3, 9, 14, 12, 16, 10, 10, 150, 68, 23, 'Boccob', 'Female', (SELECT class_id FROM classes WHERE class_name = 'Wizard'), (SELECT race_id FROM races WHERE race_name = 'Human'), 16, 'LN', 100, (SELECT user_id FROM users WHERE username = 'alexwho314'));
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Appraise'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Balance'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Bluff'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Climb'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Concentration'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Craft'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Decipher Script'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Diplomacy'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Disable Device'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Disguise'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Escape Artist'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Forgery'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Gather Information'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Handle Animal'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Heal'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Hide'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Intimidate'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Jump'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (arcana)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (architecture and engineering)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (dungeoneering)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (geography)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (history)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (local)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (nature)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (nobility and royalty)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (religion)'), 1);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Knowledge (the planes)'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Listen'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Move Silently'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Open Lock'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Perform'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Profession'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Ride'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Search'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Sense Motive'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Sleight of Hand'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Speak Language'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Spellcraft'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Spot'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Survival'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Swim'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Tumble'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Use Magic Device'), 0);
INSERT INTO characters_skills (character_id, skill_id, skill_rank) VALUES (1, (SELECT skill_id FROM skills WHERE skill_name = 'Use Rope'), 0);