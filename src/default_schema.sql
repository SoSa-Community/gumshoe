DROP SCHEMA IF EXISTS `main`;
CREATE SCHEMA IF NOT EXISTS `main` DEFAULT CHARACTER SET utf8mb4 ;

USE `main`;

CREATE TABLE `ticket` (
    `id`          BIGINT(20) NOT NULL AUTO_INCREMENT,
    `title`       VARCHAR(255) NOT NULL COMMENT 'Title to summarise ticket content',
    `description` LONGTEXT NOT NULL COMMENT 'The full ticket content',
    `status_id`   INT(20) UNSIGNED NOT NULL COMMENT 'Current ticket state',
    `type_id`     INT(20) UNSIGNED NOT NULL COMMENT 'What type of ticket this is.',
    `privacy`     ENUM('public','private') NOT NULL DEFAULT 'private' COMMENT 'Ticket user visibility',
    `lock_mode`   ENUM('full','partial', 'anyone') NOT NULL DEFAULT 'partial' COMMENT 'Who can add to this ticket?',
    `slug`        VARCHAR(100) NOT NULL COMMENT 'Slug used to identify this ticket as a URL',
    `creator_id`  BIGINT(20) UNSIGNED NOT NULL COMMENT 'Who created this ticket.',
    `owner_id`    BIGINT(20) UNSIGNED NOT NULL COMMENT 'The active owner of the ticket',
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE `status` (
    `id`          INT NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255) NOT NULL,
    `seq`         INT DEFAULT 0,
    `creator_id`  BIGINT(20) UNSIGNED NOT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);

CREATE TABLE `type` (
    `id`          INT NOT NULL AUTO_INCREMENT,
    `name`        LONGTEXT NOT NULL,
    `seq`         INT DEFAULT 0,
    `creator_id`  BIGINT(20) UNSIGNED NOT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
);