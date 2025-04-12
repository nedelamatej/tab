/*
 * Bakalarska prace
 * MODELOVANI A ANALYZA TRAJEKTORII SOFTBALLOVEHO NADHOZU
 *
 * Vysoke uceni technicke v Brne
 * Fakulta informacnich technologii
 * Ustav pocitacove grafiky a multimedii
 *
 * Autor:   Matej Nedela
 * Vedouci: Ing. Tomas Milet,
  Ph.D.
 */

/**
 * @file
 * @brief Database setup script
 *
 * @author Matej Nedela
 */

CREATE TABLE country (
  id              INT AUTO_INCREMENT NOT NULL,
  name            VARCHAR(63) NOT NULL,
  code            VARCHAR(3) NOT NULL,
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE event (
  id              INT AUTO_INCREMENT NOT NULL,
  organization_id INT NOT NULL,
  country_id      INT NOT NULL,
  name            VARCHAR(63) NOT NULL,
  date            DATE DEFAULT NULL,
  city            VARCHAR(63) DEFAULT NULL,
  details         VARCHAR(255) DEFAULT NULL,
  INDEX IDX_3BAE0AA732C8A3DE (organization_id),
  INDEX IDX_3BAE0AA7F92F3E70 (country_id),
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE organization (
  id              INT AUTO_INCREMENT NOT NULL,
  country_id      INT NOT NULL,
  name            VARCHAR(63) NOT NULL,
  date            DATE DEFAULT NULL,
  city            VARCHAR(63) DEFAULT NULL,
  details         VARCHAR(255) DEFAULT NULL,
  INDEX IDX_C1EE637CF92F3E70 (country_id),
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE pitch (
  id              INT AUTO_INCREMENT NOT NULL,
  event_id        INT DEFAULT NULL,
  pitcher_id      INT DEFAULT NULL,
  type_id         INT DEFAULT NULL,
  date            DATE NOT NULL,
  time            TIME NOT NULL,
  t               DOUBLE PRECISION NOT NULL,
  alpha           DOUBLE PRECISION NOT NULL,
  omega           DOUBLE PRECISION NOT NULL,
  x_0             DOUBLE PRECISION NOT NULL,
  y_0             DOUBLE PRECISION NOT NULL,
  z_0             DOUBLE PRECISION NOT NULL,
  v_0             DOUBLE PRECISION NOT NULL,
  phi_0           DOUBLE PRECISION NOT NULL,
  theta_0         DOUBLE PRECISION NOT NULL,
  x_t             DOUBLE PRECISION NOT NULL,
  y_t             DOUBLE PRECISION NOT NULL,
  z_t             DOUBLE PRECISION NOT NULL,
  v_t             DOUBLE PRECISION DEFAULT NULL,
  phi_t           DOUBLE PRECISION DEFAULT NULL,
  theta_t         DOUBLE PRECISION DEFAULT NULL,
  x_1             DOUBLE PRECISION DEFAULT NULL,
  y_1             DOUBLE PRECISION DEFAULT NULL,
  z_1             DOUBLE PRECISION DEFAULT NULL,
  x_2             DOUBLE PRECISION DEFAULT NULL,
  y_2             DOUBLE PRECISION DEFAULT NULL,
  z_2             DOUBLE PRECISION DEFAULT NULL,
  x_3             DOUBLE PRECISION DEFAULT NULL,
  y_3             DOUBLE PRECISION DEFAULT NULL,
  z_3             DOUBLE PRECISION DEFAULT NULL,
  c_d             DOUBLE PRECISION DEFAULT NULL,
  c_l             DOUBLE PRECISION DEFAULT NULL,
  delta           DOUBLE PRECISION DEFAULT NULL,
  INDEX IDX_279FBED971F7E88B (event_id),
  INDEX IDX_279FBED99A04123C (pitcher_id),
  INDEX IDX_279FBED9C54C8C93 (type_id),
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE pitcher (
  id              INT AUTO_INCREMENT NOT NULL,
  organization_id INT NOT NULL,
  country_id      INT NOT NULL,
  first_name      VARCHAR(31) NOT NULL,
  last_name       VARCHAR(31) NOT NULL,
  date            DATE DEFAULT NULL,
  city            VARCHAR(63) DEFAULT NULL,
  details         VARCHAR(255) DEFAULT NULL,
  INDEX IDX_9B7B5A4F32C8A3DE (organization_id),
  INDEX IDX_9B7B5A4FF92F3E70 (country_id),
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

CREATE TABLE type (
  id              INT AUTO_INCREMENT NOT NULL,
  name            VARCHAR(63) NOT NULL,
  code            VARCHAR(3) NOT NULL,
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA732C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id);
ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id);
ALTER TABLE organization ADD CONSTRAINT FK_C1EE637CF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id);
ALTER TABLE pitch ADD CONSTRAINT FK_279FBED971F7E88B FOREIGN KEY (event_id) REFERENCES event (id);
ALTER TABLE pitch ADD CONSTRAINT FK_279FBED99A04123C FOREIGN KEY (pitcher_id) REFERENCES pitcher (id);
ALTER TABLE pitch ADD CONSTRAINT FK_279FBED9C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id);
ALTER TABLE pitcher ADD CONSTRAINT FK_9B7B5A4F32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id);
ALTER TABLE pitcher ADD CONSTRAINT FK_9B7B5A4FF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id);
