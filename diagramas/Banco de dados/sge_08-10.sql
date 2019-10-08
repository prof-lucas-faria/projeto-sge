-- MySQL Script generated by MySQL Workbench
-- Tue Oct  8 14:03:23 2019
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema sge
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema sge
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `sge` DEFAULT CHARACTER SET utf8 ;
USE `sge` ;

-- -----------------------------------------------------
-- Table `sge`.`evento`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`evento` (
  `evento_id` INT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NULL,
  `data_inicio` DATE NULL,
  `data_termino` DATE NULL,
  `descricao` TEXT NULL,
  `data_prorrogacao` DATE NULL,
  `evento_inicio` DATE NULL,
  `evento_termino` DATE NULL,
  `inativo` INT NULL,
  PRIMARY KEY (`evento_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`atividade`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`atividade` (
  `atividade_id` INT NOT NULL AUTO_INCREMENT,
  `evento_id` INT NOT NULL,
  `titulo` VARCHAR(255) NULL,
  `responsavel` VARCHAR(255) NULL,
  `carga_horaria` INT(3) NULL,
  `datahora_inicio` DATETIME NULL,
  `datahora_termino` DATETIME NULL,
  `local` VARCHAR(255) NULL,
  `quantidade_vaga` INT(4) NULL,
  `tipo` VARCHAR(255) NULL,
  `inativo` INT NULL,
  PRIMARY KEY (`atividade_id`),
  INDEX `fk_atividade_evento_idx` (`evento_id` ASC) VISIBLE,
  CONSTRAINT `fk_atividade_evento`
    FOREIGN KEY (`evento_id`)
    REFERENCES `sge`.`evento` (`evento_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`usuario` (
  `usuario_id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `cpf` VARCHAR(14) NOT NULL,
  `senha` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `data_nascimento` DATE NOT NULL,
  `telefone` VARCHAR(20) NOT NULL,
  `endereco` VARCHAR(255) NOT NULL,
  `bairro` VARCHAR(100) NOT NULL,
  `estado` VARCHAR(2) NOT NULL,
  `cidade` VARCHAR(100) NOT NULL,
  `cep` VARCHAR(10) NOT NULL,
  `nacionalidade` VARCHAR(50) NOT NULL,
  `ocupacao` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`usuario_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`presenca`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`presenca` (
  `presenca` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `atividade_id` INT NOT NULL,
  PRIMARY KEY (`presenca`),
  INDEX `fk_presenca_usuario1_idx` (`usuario_id` ASC) VISIBLE,
  INDEX `fk_presenca_atividade1_idx` (`atividade_id` ASC) VISIBLE,
  CONSTRAINT `fk_presenca_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `sge`.`usuario` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_presenca_atividade1`
    FOREIGN KEY (`atividade_id`)
    REFERENCES `sge`.`atividade` (`atividade_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`tematica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`tematica` (
  `id_tematica` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(100) NULL,
  PRIMARY KEY (`id_tematica`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`trabalho`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`trabalho` (
  `trabalho_id` INT NOT NULL,
  `evento_id` INT NOT NULL,
  `titulo` VARCHAR(200) NULL,
  `arquivo_identificado` VARCHAR(100) NULL,
  `arquivo_nao_identificado` VARCHAR(100) NULL,
  `status` VARCHAR(100) NULL,
  `id_tematica` INT NOT NULL,
  `apresentador` INT NOT NULL,
  `autor` INT NOT NULL,
  PRIMARY KEY (`trabalho_id`),
  INDEX `fk_submissao_evento1_idx` (`evento_id` ASC) VISIBLE,
  INDEX `fk_trabalho_tematica1_idx` (`id_tematica` ASC) VISIBLE,
  INDEX `fk_trabalho_usuario1_idx` (`apresentador` ASC) VISIBLE,
  INDEX `fk_trabalho_usuario2_idx` (`autor` ASC) VISIBLE,
  CONSTRAINT `fk_submissao_evento1`
    FOREIGN KEY (`evento_id`)
    REFERENCES `sge`.`evento` (`evento_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trabalho_tematica1`
    FOREIGN KEY (`id_tematica`)
    REFERENCES `sge`.`tematica` (`id_tematica`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trabalho_usuario1`
    FOREIGN KEY (`apresentador`)
    REFERENCES `sge`.`usuario` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trabalho_usuario2`
    FOREIGN KEY (`autor`)
    REFERENCES `sge`.`usuario` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`avaliador_has_submissao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`avaliador_has_submissao` (
  `id_avaliador` INT NOT NULL,
  `id_trabalho` INT NOT NULL,
  `parecer` VARCHAR(1000) NULL,
  `correcao` VARCHAR(100) NULL,
  `sugestao` VARCHAR(1000) NULL,
  PRIMARY KEY (`id_avaliador`, `id_trabalho`),
  INDEX `fk_avaliador_has_submissao_submissao1_idx` (`id_trabalho` ASC) VISIBLE,
  CONSTRAINT `fk_avaliador_has_submissao_submissao1`
    FOREIGN KEY (`id_trabalho`)
    REFERENCES `sge`.`trabalho` (`trabalho_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`permissao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`permissao` (
  `evento_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `permissao` INT NOT NULL DEFAULT 0,
  INDEX `fk_evento_has_usuario_usuario1_idx` (`usuario_id` ASC) VISIBLE,
  INDEX `fk_evento_has_usuario_evento1_idx` (`evento_id` ASC) VISIBLE,
  PRIMARY KEY (`evento_id`, `usuario_id`),
  CONSTRAINT `fk_evento_has_usuario_evento1`
    FOREIGN KEY (`evento_id`)
    REFERENCES `sge`.`evento` (`evento_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_has_usuario_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `sge`.`usuario` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`usuario_has_Trabalho`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`usuario_has_Trabalho` (
  `usuario_id` INT NOT NULL,
  `trabalho_id` INT NOT NULL,
  INDEX `fk_usuario_has_Trabalho_Trabalho1_idx` (`trabalho_id` ASC) VISIBLE,
  INDEX `fk_usuario_has_Trabalho_usuario1_idx` (`usuario_id` ASC) VISIBLE,
  PRIMARY KEY (`usuario_id`, `trabalho_id`),
  CONSTRAINT `fk_usuario_has_Trabalho_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `sge`.`usuario` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_usuario_has_Trabalho_Trabalho1`
    FOREIGN KEY (`trabalho_id`)
    REFERENCES `sge`.`trabalho` (`trabalho_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`avaliador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`avaliador` (
  `avaliador_id` INT NOT NULL,
  `usuario_id` INT NOT NULL,
  `areaAtuacao` VARCHAR(100) NULL,
  PRIMARY KEY (`avaliador_id`),
  INDEX `fk_avaliador_usuario1_idx` (`usuario_id` ASC) VISIBLE,
  CONSTRAINT `fk_avaliador_usuario1`
    FOREIGN KEY (`usuario_id`)
    REFERENCES `sge`.`usuario` (`usuario_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`avaliacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`avaliacao` (
  `trabalho_id` INT NOT NULL,
  `avaliador_id` INT NOT NULL,
  `correcao` VARCHAR(100) NULL,
  `parecer` VARCHAR(100) NULL,
  PRIMARY KEY (`trabalho_id`, `avaliador_id`),
  INDEX `fk_trabalho_has_avaliador_avaliador1_idx` (`avaliador_id` ASC) VISIBLE,
  INDEX `fk_trabalho_has_avaliador_trabalho1_idx` (`trabalho_id` ASC) VISIBLE,
  CONSTRAINT `fk_trabalho_has_avaliador_trabalho1`
    FOREIGN KEY (`trabalho_id`)
    REFERENCES `sge`.`trabalho` (`trabalho_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trabalho_has_avaliador_avaliador1`
    FOREIGN KEY (`avaliador_id`)
    REFERENCES `sge`.`avaliador` (`avaliador_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sge`.`evento_has_tematica`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sge`.`evento_has_tematica` (
  `evento_id` INT NOT NULL,
  `id_tematica` INT NOT NULL,
  PRIMARY KEY (`evento_id`, `id_tematica`),
  INDEX `fk_evento_has_tematica_tematica1_idx` (`id_tematica` ASC) VISIBLE,
  INDEX `fk_evento_has_tematica_evento1_idx` (`evento_id` ASC) VISIBLE,
  CONSTRAINT `fk_evento_has_tematica_evento1`
    FOREIGN KEY (`evento_id`)
    REFERENCES `sge`.`evento` (`evento_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_evento_has_tematica_tematica1`
    FOREIGN KEY (`id_tematica`)
    REFERENCES `sge`.`tematica` (`id_tematica`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
