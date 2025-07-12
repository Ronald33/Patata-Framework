-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Table `terminales`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `terminales` (
  `term_id` INT NOT NULL AUTO_INCREMENT,
  `term_nombre` VARCHAR(64) NOT NULL,
  `term_habilitado` TINYINT NOT NULL,
  PRIMARY KEY (`term_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `patata`.`terminales`
-- -----------------------------------------------------
START TRANSACTION;
USE `patata`;
INSERT INTO `patata`.`terminales` (`term_id`, `term_nombre`, `term_habilitado`) VALUES (1, 'Caja 1', 1);

COMMIT;

