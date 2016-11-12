DROP DATABASE IF EXISTS rpg;

CREATE DATABASE rpg DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE rpg;

DROP USER IF EXISTS 'rpg_user'@'localhost';
CREATE USER 'rpg_user'@'localhost' IDENTIFIED BY 'This_is_a_passphrase!';
GRANT SELECT, INSERT, UPDATE, DELETE ON rpg.* TO 'rpg_user'@'localhost';

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
	CHECK (str_attr >= 0),
	dex_attr INT,
	CHECK (dex_attr >= 0),
	con_attr INT,
	CHECK (con_attr >= 0),
	int_attr INT,
	CHECK (int_attr >= 0),
	wis_attr INT NOT NULL, # Required in order to be considered a creature
	CHECK (wis_attr > 0),
	cha_attr INT NOT NULL, # Required in order to be considered a creature
	CHECK (cha_attr > 0),
	weight INT, # Non-corporeal can be massless
	CHECK (weight >= 0),
	height INT,
	CHECK (height >= 0),
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

INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Barbarian', 'Good', 'Good', 'Poor', 'Poor', 12, 4, 'Not L');
INSERT INTO classes (class_name, base_attack, fort_save, ref_save, will_save, hd, skill_points, alignment) VALUES ('Bard', 'Average', 'Poor', 'Good', 'Good', 6, 6, 'Not L');
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
	max_dex INT,
	armor_check_penalty INT NOT NULL,
	arcane_spell_failure_chance DECIMAL(2, 2) NOT NULL,
	weight INT NOT NULL
);

INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Padded', 5, 1, 8, 0, .05, 10);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Leather', 10, 2, 6, 0, .10, 15);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Studded leather', 25, 3, 5, -1, .15, 20);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Chain shirt', 100, 4, 4, -2, .20, 25);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Hide', 15, 3, 4, -3, .20, 25);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Scale mail', 50, 4, 3, -4, .25, 30);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Chain mail', 150, 5, 2, -5, .30, 40);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Breastplate', 200, 5, 3, -4, .25, 30);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Splint mail', 200, 6, 0, -7, .40, 45);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Banded mail', 250, 6, 1, -6, .35, 35);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Half-plate', 600, 7, 0, -7, .40, 50);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Full plate', 1500, 8, 1, -6, .35, 50);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Buckler', 15, 1, NULL, -1, .05, 5);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Shield, light wooden', 3, 1, NULL, -1, .05, 5);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Shield, light steel', 9, 1, NULL, -1, .05, 6);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Shield, heavy wooden', 7, 2, NULL, -2, .15, 10);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Shield, heavy steel', 20, 2, NULL, -2, .15, 15);
INSERT INTO armor(armor_name, cost, armor_bonus, max_dex, armor_check_penalty, arcane_spell_failure_chance, weight) VALUES ('Shield, tower', 30, 4, 2, -10, .50, 45);

CREATE TABLE generic_items
(
	generic_item_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(generic_item_id),
	generic_item_name VARCHAR(30) NOT NULL,
	UNIQUE (generic_item_name),
	cost DECIMAL(10, 2) NOT NULL,
	weight DECIMAL(10, 1)
);

# Adventuring gear
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Backpack (empty)', 2, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Barrel (empty)', 2, 30);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Basket (empty)', .4, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Bedroll', .1, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Bell', 1, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Blanket, winter', .5, 3);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Block and tackle', 5, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Bottle, wine, glass', 2, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Bucket (empty)', .5, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Caltrops', 1, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Candle', .01, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Canvas (sq. yd.)', .1, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Case, map or scroll', 1, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Chain (10 ft.)', 30, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Chalk, 1 piece', .01, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Chest (empty)', 2, 25);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Crowbar', 2, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Firewood (per day)', .01, 20);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Fishhook', .1, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Fishing net, 25 sq. ft.', 4, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Flask (empty)', .03, 1.5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Flint and steel', 1, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Grappling hook', 1, 4);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Hammer', .5, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Ink (1 oz. vial)', 8, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Inkpen', .1, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Jug, clay', .03, 9);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Ladder, 10-foot', .05, 20);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Lamp, common', .1, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Lantern, bullseye', 12, 3);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Lantern, hooded', 7, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Lock (very simple)', 20, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Lock (average)', 40, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Lock (good)', 80, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Lock (amazing)', 150, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Manacles', 15, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Manacles, masterwork', 50, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Mirror, small steel', 10, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Mug/Tankard, clay', .02, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Oil (1-pint flask)', .1, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Paper (sheet)', .4, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Parchment (sheet)', .2, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Pick, miner\'s', 3, 10);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Pitcher, clay', .02, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Piton', .01, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Pole, 10-foot', .2, 8);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Pot, iron', .5, 10);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Pouch, belt (empty)', 1, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Ram, portable', 10, 20);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Rations, trail (per day)', .5, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Rope, hempen (50 ft.)', 1, 10);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Rope, slk (50 ft.)', 10, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Sack (empty)', .1, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Sealing wax', 1, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Sewing needle', .5, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Signal whistle', .8, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Signet ring', 5, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Sledge', 1, 10);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Soap (per lb.)', .5, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Spade or shovel', 2, 8);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Spyglass', 1000, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Tent', 10, 20);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Torch', .01, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Vial, ink or potion', 1, .1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Waterskin', 1, 4);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Whetstone', .02, 1);

# Special substances and items
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Acid (flask)', 10, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Alchemist\'s fire (flask)', 20, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Antitoxin (vial)', 50, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Everburing torch', 110, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Holy water (flask)', 25, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Smokestick', 20, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Sunrod', 2, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Tanglefoot bag', 50, 4);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Thunderstone', 30, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Tindertwig', 1, NULL);

# Tools and skill kits
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Alchemist\s lab', 500, 40);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Artisan\'s tools', 5, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Artisan\'s tools, masterwork', 55, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Climber\'s kit', 80, 5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Disguise kit', 50, 8);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Healer\'s kit', 50, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Holly and mistletoe', 0, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Holy symbol, wooden', 1, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Holy symbol, silver', 25, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Hourglass', 25, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Magnifying glass', 100, NULL);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Musical instrument, common', 5, 3);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Musical instrument, masterwork', 100, 3);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Scale, merchant\'s', 2, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Spell component pouch', 5, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Spellbook, wizard\'s (blank)', 15, 3);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Thieves\' tools', 30, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Thieves\' tools, masterwork', 100, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Tool, masterwork', 50, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Water clock', 1000, 200);

# Clothing
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Artisan\'s outfit', 1, 4);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Cleric\'s vestments', 5, 6);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Cold weather outfit', 8, 7);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Courtier\'s outfit', 30, 6);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Entertainer\'s outfit', 3, 4);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Explorer\'s outfit', 10, 8);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Monk\'s outfit', 5, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Noble\'s outfit', 75, 10);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Peasant\'s outfit', .1, 2);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Royal outfit', 200, 15);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Scholar\'s outfit', 5, 6);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Traveler\'s outfit', 1, 5);

# Food, drink, and lodging
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Ale, gallon', .2, 8);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Ale, mug', .04, 1);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Bread, loaf of', .02, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Cheese, hunk of', .1, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Meat, chunk of', .3, .5);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Wine, common (pitcher)', .2, 6);
INSERT INTO generic_items(generic_item_name, cost, weight) VALUES ('Wine, fine (bottle)', 10, 1.5);

CREATE TABLE weapons
(
	weapon_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(weapon_id),
	weapon_name VARCHAR(30) NOT NULL,
	UNIQUE (weapon_name),
	cost DECIMAL(10, 2) NOT NULL,
	damage VARCHAR(10) NOT NULL, # For Medium character
	critical VARCHAR(10) NOT NULL,
	weapon_range INT, # Feet
	weight INT,
	damage_type VARCHAR(3) NOT NULL
);

INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Gauntlet', 2, '1d3', 'x2', NULL, 1, 'B');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Unarmed strike', 0, '1d3', 'x2', NULL, 0, 'B');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Dagger', 2, '1d4', '19-20/x2', 10, 1, 'PS');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Dagger, punching', 2, '1d4', 'x3', NULL, 1, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Gauntlet, spiked', 5, '1d4', 'x2', NULL, 1, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Mace, light', 5, '1d6', 'x2', NULL, 4, 'B');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Sickle', 6, '1d6', 'x2', NULL, 2, 'S');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Club', 0, '1d6', 'x2', 10, 3, 'B');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Mace, heavy', 12, '1d8', 'x2', NULL, 8, 'B');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Morningstar', 8, '1d8', 'x2', NULL, 6, 'BP');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Shortspear', 1, '1d6', 'x2', 20, 3, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Longspear', 5, '1d8', 'x3', NULL, 9, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Quarterstaff', 0, '1d6/1d6', 'x2', NULL, 4, 'B');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Spear', 2, '1d8', 'x3', 20, 6, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Crossbow, heavy', 50, '1d10', '19-20/x2', 120, 8, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Crossbow, light', 35, '1d8', '19-20/x2', 80, 4, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Dart', .5, '1d4', 'x2', 20, .5, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Javelin', 1, '1d6', 'x2', 30, 2, 'P');
INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('Sling', 0, '1d4', 'x2', 50, 0, 'B');
# INSERT INTO weapons(weapon_name, cost, damage, critical, weapon_range, weight, damage_type) VALUES ('', , '', '', , , '');
# Possibly add more than simple weapons

CREATE TABLE skills_races
(
	skill_id INT NOT NULL,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	race_id INT NOT NULL,
	FOREIGN KEY (race_id) REFERENCES races(race_id),
	bonus INT NOT NULL,
	PRIMARY KEY (skill_id, race_id)
);

INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Listen'), (SELECT race_id FROM races WHERE race_name = 'Elf'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Search'), (SELECT race_id FROM races WHERE race_name = 'Elf'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Spot'), (SELECT race_id FROM races WHERE race_name = 'Elf'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Listen'), (SELECT race_id FROM races WHERE race_name = 'Gnome'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Listen'), (SELECT race_id FROM races WHERE race_name = 'Half-elf'), 1);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Search'), (SELECT race_id FROM races WHERE race_name = 'Half-elf'), 1);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Spot'), (SELECT race_id FROM races WHERE race_name = 'Half-elf'), 1);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Diplomacy'), (SELECT race_id FROM races WHERE race_name = 'Half-elf'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Gather Information'), (SELECT race_id FROM races WHERE race_name = 'Half-elf'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Climb'), (SELECT race_id FROM races WHERE race_name = 'Halfling'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Jump'), (SELECT race_id FROM races WHERE race_name = 'Halfling'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Listen'), (SELECT race_id FROM races WHERE race_name = 'Halfling'), 2);
INSERT INTO skills_races(skill_id, race_id, bonus) VALUES ((SELECT skill_id FROM skills WHERE skill_name = 'Move Silently'), (SELECT race_id FROM races WHERE race_name = 'Halfling'), 2);

CREATE TABLE skills_classes
(
	skill_id INT NOT NULL,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	class_id INT NOT NULL,
	FOREIGN KEY (class_id) REFERENCES classes(class_id),
	cross_class BOOLEAN NOT NULL,
	PRIMARY KEY (skill_id, class_id)
);

CREATE TABLE characters_skills
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id) ON DELETE CASCADE,
	skill_id INT NOT NULL,
	FOREIGN KEY (skill_id) REFERENCES skills(skill_id),
	skill_rank INT NOT NULL,
	CHECK (bonus >= 0),
	PRIMARY KEY (character_id, skill_id)
);

CREATE TABLE characters_feats
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id) ON DELETE CASCADE,
	feat_id INT NOT NULL,
	FOREIGN KEY (feat_id) REFERENCES feats(feat_id),
	PRIMARY KEY (character_id, feat_id)
);

CREATE TABLE characters_languages
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id) ON DELETE CASCADE,
	language_id INT NOT NULL,
	FOREIGN KEY (language_id) REFERENCES languages(language_id),
	PRIMARY KEY (character_id, language_id)
);

CREATE TABLE characters_spells
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id) ON DELETE CASCADE,
	spell_id INT NOT NULL,
	FOREIGN KEY (spell_id) REFERENCES spells(spell_id),
	PRIMARY KEY (character_id, spell_id)
);

CREATE TABLE characters_armor
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id) ON DELETE CASCADE,
	armor_id INT NOT NULL,
	FOREIGN KEY (armor_id) REFERENCES armors(armor_id),
	quantity INT NOT NULL,
	location VARCHAR(30),
	CHECK (location IN ('EQUIPPED')),
	PRIMARY KEY (character_id, armor_id)
);

CREATE TABLE characters_generic_items
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id) ON DELETE CASCADE,
	generic_item_id INT NOT NULL,
	FOREIGN KEY (generic_item_id) REFERENCES generic_items(generic_item_id),
	quantity INT NOT NULL,
	location VARCHAR(30),
	CHECK (location IN ('EQUIPPED')),
	PRIMARY KEY (character_id, generic_item_id)
);

CREATE TABLE characters_weapons
(
	character_id INT NOT NULL,
	FOREIGN KEY (character_id) REFERENCES characters(character_id) ON DELETE CASCADE,
	weapon_id INT NOT NULL,
	FOREIGN KEY (weapon_id) REFERENCES weapons(weapon_id),
	quantity INT NOT NULL,
	location VARCHAR(30),
	CHECK (location IN ('EQUIPPED')),
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

CREATE TABLE r_base_ages
(
	race_id INT NOT NULL,
	FOREIGN KEY (race_id) REFERENCES races(race_id),
	base_age INT NOT NULL,
	CHECK (base_age >= 0),
	PRIMARY KEY (race_id)
);

INSERT INTO r_base_ages(race_id, base_age) VALUES ((SELECT race_id FROM races WHERE race_name = 'Human'), 15);
INSERT INTO r_base_ages(race_id, base_age) VALUES ((SELECT race_id FROM races WHERE race_name = 'Dwarf'), 40);
INSERT INTO r_base_ages(race_id, base_age) VALUES ((SELECT race_id FROM races WHERE race_name = 'Elf'), 110);
INSERT INTO r_base_ages(race_id, base_age) VALUES ((SELECT race_id FROM races WHERE race_name = 'Gnome'), 40);
INSERT INTO r_base_ages(race_id, base_age) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-elf'), 20);
INSERT INTO r_base_ages(race_id, base_age) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-orc'), 14);
INSERT INTO r_base_ages(race_id, base_age) VALUES ((SELECT race_id FROM races WHERE race_name = 'Halfling'), 20);

CREATE TABLE r_class_types
(
	class_type_id INT NOT NULL,
	class_id INT NOT NULL,
	FOREIGN KEY (class_id) REFERENCES classes(class_id),
	PRIMARY KEY (class_type_id, class_id)
);

INSERT INTO r_class_types(class_type_id, class_id) VALUES (1, (SELECT class_id FROM classes WHERE class_name = 'Barbarian'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (1, (SELECT class_id FROM classes WHERE class_name = 'Rogue'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (1, (SELECT class_id FROM classes WHERE class_name = 'Sorcerer'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (2, (SELECT class_id FROM classes WHERE class_name = 'Bard'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (2, (SELECT class_id FROM classes WHERE class_name = 'Fighter'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (2, (SELECT class_id FROM classes WHERE class_name = 'Paladin'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (2, (SELECT class_id FROM classes WHERE class_name = 'Ranger'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (3, (SELECT class_id FROM classes WHERE class_name = 'Cleric'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (3, (SELECT class_id FROM classes WHERE class_name = 'Druid'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (3, (SELECT class_id FROM classes WHERE class_name = 'Monk'));
INSERT INTO r_class_types(class_type_id, class_id) VALUES (3, (SELECT class_id FROM classes WHERE class_name = 'Wizard'));

CREATE TABLE r_additional_ages
(
	race_id INT NOT NULL,
	FOREIGN KEY (race_id) REFERENCES races(race_id),
	class_type_id INT NOT NULL,
	FOREIGN KEY (class_type_id) REFERENCES r_class_types(class_type_id),
	num_dice INT NOT NULL,
	CHECK (num_dice > 0),
	sides_dice INT NOT NULL,
	CHECK (sides_dice > 0)
);

INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Human'), 1, 1, 4);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Human'), 2, 1, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Human'), 3, 2, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Dwarf'), 1, 3, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Dwarf'), 2, 5, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Dwarf'), 3, 7, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Elf'), 1, 4, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Elf'), 2, 6, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Elf'), 3, 10, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Gnome'), 1, 4, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Gnome'), 2, 6, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Gnome'), 3, 9, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-elf'), 1, 1, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-elf'), 2, 2, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-elf'), 3, 3, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-orc'), 1, 1, 4);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-orc'), 2, 1, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-orc'), 3, 2, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Halfling'), 1, 2, 4);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Halfling'), 2, 3, 6);
INSERT INTO r_additional_ages(race_id, class_type_id, num_dice, sides_dice) VALUES ((SELECT race_id FROM races WHERE race_name = 'Halfling'), 3, 4, 6);

CREATE TABLE r_heights_weights
(
	race_id INT NOT NULL,
	FOREIGN KEY (race_id) REFERENCES races(race_id),
	gender VARCHAR(10),
	base_height INT NOT NULL,
	CHECK (base_height >= 0),
	num_dice_height INT NOT NULL,
	CHECK (num_dice_height > 0),
	sides_dice_height INT NOT NULL,
	CHECK (sides_dice_height > 1),
	base_weight INT NOT NULL,
	CHECK (base_weight >= 0),
	num_dice_weight INT NOT NULL,
	CHECK (num_dice_weight > 0),
	sides_dice_weight INT NOT NULL,
	CHECK (sides_dice_weight > 0)
);

INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Human'), 'Male', 58, 2, 10, 120, 2, 4);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Human'), 'Female', 53, 2, 10, 85, 2, 4);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Dwarf'), 'Male', 45, 2, 4, 130, 2, 6);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Dwarf'), 'Female', 43, 2, 4, 100, 2, 6);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Elf'), 'Male', 53, 2, 6, 85, 1, 6);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Elf'), 'Female', 53, 2, 6, 80, 1, 6);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Gnome'), 'Male', 36, 2, 4, 40, 1, 1);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Gnome'), 'Female', 34, 2, 4, 35, 1, 1);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-elf'), 'Male', 55, 2, 8, 100, 2, 4);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-elf'), 'Female', 53, 2, 8, 80, 2, 4);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-orc'), 'Male', 58, 2, 12, 150, 2, 6);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Half-orc'), 'Female', 53, 2, 12, 110, 2, 6);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Halfling'), 'Male', 32, 2, 4, 30, 1, 1);
INSERT INTO r_heights_weights(race_id, gender, base_height, num_dice_height, sides_dice_height, base_weight, num_dice_weight, sides_dice_weight) VALUES ((SELECT race_id FROM races WHERE race_name = 'Halfling'), 'Female', 30, 2, 4, 25, 1, 1);