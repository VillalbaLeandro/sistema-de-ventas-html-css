-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema drinkstore_db
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema drinkstore_db
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `drinkstore_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `drinkstore_db` ;

-- -----------------------------------------------------
-- Table `drinkstore_db`.`categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`categoria` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`categoria_cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`categoria_cliente` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`cliente` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `apellido` VARCHAR(45) NOT NULL,
  `direccion` VARCHAR(45) NOT NULL,
  `fechaNacimiento` DATETIME NOT NULL,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  `telefono` VARCHAR(45) NOT NULL,
  `cuil_cuit` VARCHAR(45) NOT NULL,
  `dni` VARCHAR(45) NOT NULL,
  `categoria_cliente_id` INT NOT NULL,
  `pass` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_cliente_categoria_cliente1_idx` (`categoria_cliente_id` ASC) ,
  CONSTRAINT `fk_cliente_categoria_cliente1`
    FOREIGN KEY (`categoria_cliente_id`)
    REFERENCES `drinkstore_db`.`categoria_cliente` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`producto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`producto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `descipcion` VARCHAR(45) NOT NULL,
  `precio_venta` DECIMAL(10,0) NOT NULL,
  `precio_costo` DECIMAL(10,0) NOT NULL,
  `stock` INT NOT NULL,
  `stock_minimo` VARCHAR(45) NULL DEFAULT '2',
  `categoria_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_producto_categoria1_idx` (`categoria_id` ASC) ,
  CONSTRAINT `fk_producto_categoria1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `drinkstore_db`.`categoria` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`iva`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`iva` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`mediopago`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`mediopago` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 7
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`rol`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`rol` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  `apellido` VARCHAR(45) NOT NULL,
  `tel` INT NOT NULL,
  `direccion` VARCHAR(45) NULL DEFAULT 'desconocida',
  `rol_id` INT NOT NULL DEFAULT '3',
  `email` VARCHAR(45) NULL DEFAULT NULL,
  `pass` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_usuario_rol1_idx` (`rol_id` ASC) ,
  CONSTRAINT `fk_usuario_rol1`
    FOREIGN KEY (`rol_id`)
    REFERENCES `drinkstore_db`.`rol` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`venta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`venta` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `cant` INT NOT NULL,
  `medioPago_id` INT NOT NULL,
  `factura_id` INT NOT NULL,
  `cliente_id` INT NOT NULL,
  `iva_id` INT UNSIGNED NOT NULL,
  `usuario_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_venta_medioPago_idx` (`medioPago_id` ASC) ,
  INDEX `fk_venta_cliente1_idx` (`cliente_id` ASC) ,
  INDEX `fk_venta_iva1_idx` (`iva_id` ASC) ,
  INDEX `fk_venta_usuario1_idx` (`usuario_id` ASC) ,
  CONSTRAINT `fk_venta_cliente1`
    FOREIGN KEY (`cliente_id`)
    REFERENCES `drinkstore_db`.`cliente` (`id`),
  CONSTRAINT `fk_venta_iva1`
    FOREIGN KEY (`iva_id`)
    REFERENCES `drinkstore_db`.`iva` (`id`),
  CONSTRAINT `fk_venta_medioPago`
    FOREIGN KEY (`medioPago_id`)
    REFERENCES `drinkstore_db`.`mediopago` (`id`),
  CONSTRAINT `fk_venta_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `drinkstore_db`.`usuario` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`detalle_venta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`detalle_venta` (
  `venta_id` INT NOT NULL,
  `producto_id` INT NOT NULL,
  `cantidad` INT NOT NULL,
  `monto` DECIMAL(10,2) NULL DEFAULT NULL,
  PRIMARY KEY (`venta_id`, `producto_id`),
  INDEX `fk_venta_has_producto_producto1_idx` (`producto_id` ASC) ,
  INDEX `fk_venta_has_producto_venta1_idx` (`venta_id` ASC) ,
  CONSTRAINT `fk_venta_has_producto_producto1`
    FOREIGN KEY (`producto_id`)
    REFERENCES `drinkstore_db`.`producto` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_venta_has_producto_venta1`
    FOREIGN KEY (`venta_id`)
    REFERENCES `drinkstore_db`.`venta` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`entrada_salida`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`entrada_salida` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = latin1;


-- -----------------------------------------------------
-- Table `drinkstore_db`.`efectivocaja`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `drinkstore_db`.`efectivocaja` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `monto` DECIMAL(10,0) NOT NULL,
  `descripcion` VARCHAR(45) NULL DEFAULT 'sin detallar',
  `entrada_salida_id` INT NOT NULL,
  `venta_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_efectivoCaja_entrada_salida1_idx` (`entrada_salida_id` ASC) ,
  INDEX `fk_efectivocaja_venta1_idx` (`venta_id` ASC) ,
  CONSTRAINT `fk_efectivoCaja_entrada_salida1`
    FOREIGN KEY (`entrada_salida_id`)
    REFERENCES `drinkstore_db`.`entrada_salida` (`id`),
  CONSTRAINT `fk_efectivocaja_venta1`
    FOREIGN KEY (`venta_id`)
    REFERENCES `drinkstore_db`.`venta` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
