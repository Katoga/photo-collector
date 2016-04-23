/* events */
CREATE TABLE `czkatoga`.`events` (
	`event_id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (`event_id`),
	UNIQUE `events_name` (`name`),
	UNIQUE `events_url` (`url`)
) ENGINE = InnoDB;

/* authors */
CREATE TABLE `czkatoga`.`authors` (
	`author_id` INT NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`login` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`password` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
	`roles` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
	PRIMARY KEY (`author_id`),
	UNIQUE `authors_name` (`name`),
	UNIQUE `authors_login` (`login`)
) ENGINE = InnoDB;

INSERT INTO `czkatoga`.`authors` (
	`author_id`,
	`name`,
	`login`,
	`password`,
	`roles`
)
VALUES (
	NULL,
	'Admin',
	'admin',
	'$2y$10$oX.pGHsiu2ZHf38.oOXzJek5SATIRcRu7dwnHi6MgKRVNONnKUsoa', -- "adminace"
	'admin'
);
