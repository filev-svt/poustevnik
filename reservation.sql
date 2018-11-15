-- MySQL Script generated by MySQL Workbench
-- Sun Apr 15 21:19:14 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`zarizeni`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`zarizeni` (
  `id_zarizeni` INT NOT NULL AUTO_INCREMENT,
  `nazev_zarizeni` VARCHAR(100) NOT NULL,
  `email_zarizeni` VARCHAR(255) NULL,
  `telefon_zarizeni` VARCHAR(15) NULL,
  `mesto` VARCHAR(100) NOT NULL,
  `ulice` VARCHAR(100) NULL,
  `psc` VARCHAR(5) NOT NULL,
  `cislo_popisne` VARCHAR(5) NOT NULL,
  `cislo_orientacni` VARCHAR(5) NOT NULL,
  PRIMARY KEY (`id_zarizeni`),
  UNIQUE INDEX `email_zarizeni_UNIQUE` (`email_zarizeni` ASC),
  UNIQUE INDEX `telefon_zarizeni_UNIQUE` (`telefon_zarizeni` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`jednotka`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`jednotka` (
  `id_jednotka` INT NOT NULL AUTO_INCREMENT,
  `nazev_jednotka` VARCHAR(100) NOT NULL,
  `typ_jednotka` VARCHAR(45) NOT NULL,
  `maximalni_obsazeni` INT NOT NULL,
  `popis_jednotka` TEXT(1000) NULL,
  `zarizeni_id_zarizeni` INT NOT NULL,
  PRIMARY KEY (`id_jednotka`, `zarizeni_id_zarizeni`),
  INDEX `fk_jednotka_zarizeni_idx` (`zarizeni_id_zarizeni` ASC),
  CONSTRAINT `fk_jednotka_zarizeni`
    FOREIGN KEY (`zarizeni_id_zarizeni`)
    REFERENCES `mydb`.`zarizeni` (`id_zarizeni`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`personal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`personal` (
  `id_personal` INT NOT NULL AUTO_INCREMENT,
  `jmeno_personal` VARCHAR(45) NOT NULL,
  `prijmeni_personal` VARCHAR(45) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `heslo` VARCHAR(60) NOT NULL,
  `administrator` TINYINT NOT NULL,
  `email` VARCHAR(255) NULL,
  `zarizeni_id_zarizeni` INT NULL,
  PRIMARY KEY (`id_personal`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  INDEX `fk_personal_zarizeni1_idx` (`zarizeni_id_zarizeni` ASC),
  CONSTRAINT `fk_personal_zarizeni1`
    FOREIGN KEY (`zarizeni_id_zarizeni`)
    REFERENCES `mydb`.`zarizeni` (`id_zarizeni`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`cenova_sazba`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`cenova_sazba` (
  `id_cenova_sazba` INT NOT NULL AUTO_INCREMENT,
  `nazev_sazba` VARCHAR(45) NOT NULL,
  `cena` INT NOT NULL,
  `datum_od` DATE NOT NULL,
  `datum_do` DATE NOT NULL,
  `jednotka_id_jednotka` INT NOT NULL,
  `jednotka_zarizeni_id_zarizeni` INT NOT NULL,
  PRIMARY KEY (`id_cenova_sazba`, `jednotka_id_jednotka`, `jednotka_zarizeni_id_zarizeni`),
  INDEX `fk_cenova_sazba_jednotka1_idx` (`jednotka_id_jednotka` ASC, `jednotka_zarizeni_id_zarizeni` ASC),
  CONSTRAINT `fk_cenova_sazba_jednotka1`
    FOREIGN KEY (`jednotka_id_jednotka` , `jednotka_zarizeni_id_zarizeni`)
    REFERENCES `mydb`.`jednotka` (`id_jednotka` , `zarizeni_id_zarizeni`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`rezervace`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`rezervace` (
  `id_rezervace` INT NOT NULL AUTO_INCREMENT,
  `jmeno` VARCHAR(45) NOT NULL,
  `prijmeni` VARCHAR(45) NOT NULL,
  `telefon` VARCHAR(15) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `pocet_osob` VARCHAR(45) NOT NULL,
  `datum_prijezd` DATE NOT NULL,
  `datum_odjezd` DATE NOT NULL,
  `celkova_cena` INT NOT NULL,
  `token` VARCHAR(9) NOT NULL,
  `jednotka_id_jednotka` INT NOT NULL,
  `jednotka_zarizeni_id_zarizeni` INT NOT NULL,
  PRIMARY KEY (`id_rezervace`, `jednotka_id_jednotka`, `jednotka_zarizeni_id_zarizeni`),
  UNIQUE INDEX `token_UNIQUE` (`token` ASC),
  INDEX `fk_rezervace_jednotka1_idx` (`jednotka_id_jednotka` ASC, `jednotka_zarizeni_id_zarizeni` ASC),
  CONSTRAINT `fk_rezervace_jednotka1`
    FOREIGN KEY (`jednotka_id_jednotka` , `jednotka_zarizeni_id_zarizeni`)
    REFERENCES `mydb`.`jednotka` (`id_jednotka` , `zarizeni_id_zarizeni`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`poplatek`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`poplatek` (
  `id_poplatek` INT NOT NULL,
  `nazev_poplatek` VARCHAR(45) NOT NULL,
  `cena` INT NOT NULL,
  PRIMARY KEY (`id_poplatek`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`rezervace_has_poplatek`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`rezervace_has_poplatek` (
  `rezervace_id_rezervace` INT NOT NULL,
  `rezervace_jednotka_id_jednotka` INT NOT NULL,
  `rezervace_jednotka_zarizeni_id_zarizeni` INT NOT NULL,
  `poplatek_id_poplatek` INT NOT NULL,
  PRIMARY KEY (`rezervace_id_rezervace`, `rezervace_jednotka_id_jednotka`, `rezervace_jednotka_zarizeni_id_zarizeni`, `poplatek_id_poplatek`),
  INDEX `fk_rezervace_has_poplatek_poplatek1_idx` (`poplatek_id_poplatek` ASC),
  INDEX `fk_rezervace_has_poplatek_rezervace1_idx` (`rezervace_id_rezervace` ASC, `rezervace_jednotka_id_jednotka` ASC, `rezervace_jednotka_zarizeni_id_zarizeni` ASC),
  CONSTRAINT `fk_rezervace_has_poplatek_rezervace1`
    FOREIGN KEY (`rezervace_id_rezervace` , `rezervace_jednotka_id_jednotka` , `rezervace_jednotka_zarizeni_id_zarizeni`)
    REFERENCES `mydb`.`rezervace` (`id_rezervace` , `jednotka_id_jednotka` , `jednotka_zarizeni_id_zarizeni`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_rezervace_has_poplatek_poplatek1`
    FOREIGN KEY (`poplatek_id_poplatek`)
    REFERENCES `mydb`.`poplatek` (`id_poplatek`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`fotografie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`fotografie` (
  `id_fotografie` INT NOT NULL,
  `adresa` VARCHAR(255) NULL,
  `jednotka_id_jednotka` INT NOT NULL,
  `jednotka_zarizeni_id_zarizeni` INT NOT NULL,
  PRIMARY KEY (`id_fotografie`, `jednotka_id_jednotka`, `jednotka_zarizeni_id_zarizeni`),
  INDEX `fk_fotografie_jednotka1_idx` (`jednotka_id_jednotka` ASC, `jednotka_zarizeni_id_zarizeni` ASC),
  CONSTRAINT `fk_fotografie_jednotka1`
    FOREIGN KEY (`jednotka_id_jednotka` , `jednotka_zarizeni_id_zarizeni`)
    REFERENCES `mydb`.`jednotka` (`id_jednotka` , `zarizeni_id_zarizeni`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;