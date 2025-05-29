-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema inhala
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema inhala
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `inhala` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci ;
USE `inhala` ;

-- -----------------------------------------------------
-- Table `inhala`.`days`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`days` (
  `dayID` INT NOT NULL AUTO_INCREMENT,
  `dayName` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`dayID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`schedule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`schedule` (
  `scheduleId` INT NOT NULL AUTO_INCREMENT,
  `startTime` TIME NOT NULL,
  `endTime` TIME NOT NULL,
  `available` TINYINT NOT NULL DEFAULT 1,
  `days_dayID` INT NOT NULL,
  `member_document` BIGINT NOT NULL,  -- Clave foránea aquí en lugar de en members
  PRIMARY KEY (`scheduleId`),
  INDEX `fk_schedule_days1_idx` (`days_dayID` ASC) VISIBLE,
  INDEX `fk_schedule_members1_idx` (`member_document` ASC) VISIBLE,
  CONSTRAINT `fk_schedule_days1`
    FOREIGN KEY (`days_dayID`)
    REFERENCES `inhala`.`days` (`dayID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_schedule_members1`
    FOREIGN KEY (`member_document`)
    REFERENCES `inhala`.`members` (`document`)
    ON DELETE CASCADE  -- Elimina los horarios si el miembro se elimina
    ON UPDATE CASCADE
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`anamnesis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`anamnesis` (
  `anamnesisID` INT NOT NULL AUTO_INCREMENT,
  `surgicalHistory` VARCHAR(100) NOT NULL,
  `pharmacologicalBackground` VARCHAR(100) NOT NULL,
  `backgroundToxicAllergic` VARCHAR(100) NOT NULL,
  `smoke` TINYINT NOT NULL,
  `alcohol` TINYINT NOT NULL,
  `exercise` VARCHAR(30) NOT NULL,
  `familyBackground` VARCHAR(100) NULL DEFAULT NULL,
  PRIMARY KEY (`anamnesisID`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`fieldOfStudy`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`fieldOfStudy` (
  `fieldOfStudyID` INT NOT NULL AUTO_INCREMENT,
  `nameFieldStudy` VARCHAR(30) NOT NULL,
  `description` VARCHAR(45) NOT NULL,
  UNIQUE INDEX `nameFieldStudy_UNIQUE` (`nameFieldStudy` ASC) VISIBLE,
  PRIMARY KEY (`fieldOfStudyID`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`appointmentStatus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`appointmentStatus` (
  `status` VARCHAR(45) NOT NULL,
  `description` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`status`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`services`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`services` (
  `serviceID` INT NOT NULL AUTO_INCREMENT,
  `serviceName` VARCHAR(45) NOT NULL,
  `serviceDescription` VARCHAR(100) NULL DEFAULT NULL,
  `cost` FLOAT NOT NULL,
  `duration` TIME NOT NULL,
  `uniqueService` TINYINT NOT NULL DEFAULT 0,
  `fieldOfStudyID` INT NOT NULL,
  `image` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`serviceID`),
  UNIQUE INDEX `Nombre_UNIQUE` (`serviceName` ASC) VISIBLE,
  INDEX `fk_services_fieldOfStudy1_idx` (`fieldOfStudyID` ASC) VISIBLE,
  CONSTRAINT `fk_services_fieldOfStudy1`
    FOREIGN KEY (`fieldOfStudyID`)
    REFERENCES `inhala`.`fieldOfStudy` (`fieldOfStudyID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`appointmentByServices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`appointmentByServices` (
  `appointmentByServicesId` INT NOT NULL AUTO_INCREMENT,
  `services_serviceID` INT NOT NULL,
  PRIMARY KEY (`appointmentByServicesId`),
  INDEX `fk_appointmentByServices_services1_idx` (`services_serviceID` ASC) VISIBLE,
  CONSTRAINT `fk_appointmentByServices_services1`
    FOREIGN KEY (`services_serviceID`)
    REFERENCES `inhala`.`services` (`serviceID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`roles` (
  `roleID` INT NOT NULL AUTO_INCREMENT,
  `roleName` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`roleID`),
  UNIQUE INDEX `RolName_UNIQUE` (`roleName` ASC) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`members`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`members` (
  `document` BIGINT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `secondName` VARCHAR(45) NULL DEFAULT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `secondLastName` VARCHAR(45) NULL,
  `birthdate` DATE NOT NULL,
  `gender` VARCHAR(10) NOT NULL,
  `mail` VARCHAR(45) NOT NULL UNIQUE,
  `phone` BIGINT NOT NULL,
  `address` VARCHAR(50) NOT NULL,
  `occupation` VARCHAR(50) NULL,
  `RH` VARCHAR(10) NOT NULL,
  `photo` VARCHAR(255) NULL,
  `userName` VARCHAR(45) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `userType` VARCHAR(15) NOT NULL,
  `memberStatus` VARCHAR(1) NOT NULL,
  `anamnesisID` INT NULL,
  `roles_roleID` INT NOT NULL,
  PRIMARY KEY (`document`),
  INDEX `fk_users_anamnesis1_idx` (`anamnesisID` ASC) VISIBLE,
  INDEX `fk_Members_roles1_idx` (`roles_roleID` ASC) VISIBLE,
  CONSTRAINT `fk_users_anamnesis1`
    FOREIGN KEY (`anamnesisID`)
    REFERENCES `inhala`.`anamnesis` (`anamnesisID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Members_roles1`
    FOREIGN KEY (`roles_roleID`)
    REFERENCES `inhala`.`roles` (`roleID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `inhala`.`servicePackages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`servicePackages` (
  `servicePackagesID` INT NOT NULL AUTO_INCREMENT,
  `servicePackageName` VARCHAR(45) NOT NULL,
  `servicePackageDescription` VARCHAR(100) NULL,
  `cost` FLOAT NOT NULL,
  PRIMARY KEY (`servicePackagesID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`appointmentByServicesPackages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`appointmentByServicesPackages` (
  `appointmentByServicesPackagesID` INT NOT NULL AUTO_INCREMENT,
  `servicePackages_servicePackagesID` INT NOT NULL,
  PRIMARY KEY (`appointmentByServicesPackagesID`, `servicePackages_servicePackagesID`),
  INDEX `fk_appointmentByServicesPackages_servicePackages1_idx` (`servicePackages_servicePackagesID` ASC) VISIBLE,
  CONSTRAINT `fk_appointmentByServicesPackages_servicePackages1`
    FOREIGN KEY (`servicePackages_servicePackagesID`)
    REFERENCES `inhala`.`servicePackages` (`servicePackagesID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`scheduledAppointments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`scheduledAppointments` (
  `scheduledAppointmentID` INT NOT NULL AUTO_INCREMENT,
  `address` VARCHAR(45) NULL,
  `notes` VARCHAR(150) NULL,
  `date` DATE NOT NULL,
  `isHomeService` TINYINT NOT NULL DEFAULT 0,
  `MembersDocument` BIGINT NOT NULL,
  `professionalDocument` BIGINT NOT NULL,
  `schedule_scheduleId` INT NOT NULL,
  `appointmentStatus` VARCHAR(45) NOT NULL,
  `appointmentByServicesID` INT NOT NULL,
  `appointmentByServicesPackagesID` INT NOT NULL,
  PRIMARY KEY (`scheduledAppointmentID`),
  INDEX `fk_citas_appointmentStatuses1_idx` (`appointmentStatus` ASC) VISIBLE,
  INDEX `fk_appointment_appointmentByServices1_idx` (`appointmentByServicesID` ASC) VISIBLE,
  INDEX `fk_appointment_schedule1_idx` (`schedule_scheduleId` ASC) VISIBLE,
  INDEX `fk_ScheduledAppointments_Members1_idx` (`MembersDocument` ASC) VISIBLE,
  INDEX `fk_ScheduledAppointments_Members2_idx` (`professionalDocument` ASC) VISIBLE,
  INDEX `fk_scheduledAppointments_appointmentByServicesPackages1_idx` (`appointmentByServicesPackagesID` ASC) VISIBLE,
  CONSTRAINT `fk_citas_appointmentStatuses1`
    FOREIGN KEY (`appointmentStatus`)
    REFERENCES `inhala`.`appointmentStatus` (`status`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_appointmentByServices1`
    FOREIGN KEY (`appointmentByServicesID`)
    REFERENCES `inhala`.`appointmentByServices` (`appointmentByServicesId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_appointment_schedule1`
    FOREIGN KEY (`schedule_scheduleId`)
    REFERENCES `inhala`.`schedule` (`scheduleId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ScheduledAppointments_Members1`
    FOREIGN KEY (`MembersDocument`)
    REFERENCES `inhala`.`members` (`document`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ScheduledAppointments_Members2`
    FOREIGN KEY (`professionalDocument`)
    REFERENCES `inhala`.`members` (`document`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_scheduledAppointments_appointmentByServicesPackages1`
    FOREIGN KEY (`appointmentByServicesPackagesID`)
    REFERENCES `inhala`.`appointmentByServicesPackages` (`appointmentByServicesPackagesID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`followUpSheets`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`followUpSheets` (
  `followUpSheetsID` INT NOT NULL AUTO_INCREMENT,
  `evaluationdate` DATE NOT NULL,
  `detail` VARCHAR(300) NOT NULL,
  `professional_document` BIGINT NOT NULL,
  `members_document` BIGINT NOT NULL,
  PRIMARY KEY (`followUpSheetsID`),
  INDEX `fk_followUpSheets_users1_idx` (`professional_document` ASC) VISIBLE,
  INDEX `fk_followUpSheets_Members1_idx` (`members_document` ASC) VISIBLE,
  CONSTRAINT `fk_followUpSheets_users1`
    FOREIGN KEY (`professional_document`)
    REFERENCES `inhala`.`members` (`document`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_followUpSheets_Members1`
    FOREIGN KEY (`members_document`)
    REFERENCES `inhala`.`members` (`document`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`membersByServices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`membersByServices` (
  `memberByServiceID` BIGINT NOT NULL AUTO_INCREMENT,
  `members_document` BIGINT NOT NULL,
  `serviceID` INT NULL,
  `servicePackagesID` INT NULL,
  PRIMARY KEY (`memberByServiceID`),
  INDEX `fk_membersByServices_services1_idx` (`serviceID` ASC) VISIBLE,
  INDEX `fk_membersByServices_ServicePackages1_idx` (`servicePackagesID` ASC) VISIBLE,
  INDEX `fk_membersByServices_Members1_idx` (`members_document` ASC) VISIBLE,
  CONSTRAINT `fk_membersByServices_services1`
    FOREIGN KEY (`serviceID`)
    REFERENCES `inhala`.`services` (`serviceID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_membersByServices_ServicePackages1`
    FOREIGN KEY (`servicePackagesID`)
    REFERENCES `inhala`.`servicePackages` (`servicePackagesID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_membersByServices_Members1`
    FOREIGN KEY (`members_document`)
    REFERENCES `inhala`.`members` (`document`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

-- -----------------------------------------------------
-- Table `inhala`.`membersFieldsOfStudy`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`membersFieldsOfStudy` (
  `membersFieldsOfStudyID` INT NOT NULL AUTO_INCREMENT,
  `fieldOfStudy_fieldOfStudyID` INT NOT NULL,
  `members_document` BIGINT NOT NULL,
  PRIMARY KEY (`membersFieldsOfStudyID`),
  INDEX `fk_MembersFieldsOfStudy_fieldOfStudy1_idx` (`fieldOfStudy_fieldOfStudyID` ASC) VISIBLE,
  INDEX `fk_MembersFieldsOfStudy_Members1_idx` (`members_document` ASC) VISIBLE,
  CONSTRAINT `fk_MembersFieldsOfStudy_fieldOfStudy1`
    FOREIGN KEY (`fieldOfStudy_fieldOfStudyID`)
    REFERENCES `inhala`.`fieldOfStudy` (`fieldOfStudyID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_MembersFieldsOfStudy_Members1`
    FOREIGN KEY (`members_document`)
    REFERENCES `inhala`.`members` (`document`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;


-- -----------------------------------------------------
-- Table `inhala`.`servicePackageDetails`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `inhala`.`servicePackageDetails` (
  `servicePackageDetailsID` INT NOT NULL AUTO_INCREMENT,
  `quantity` INT NULL DEFAULT 1,
  `servicePackagesID` INT NOT NULL,
  `serviceID` INT NOT NULL,
  PRIMARY KEY (`servicePackageDetailsID`),
  INDEX `fk_ServicePackageDetails_ServicePackages1_idx` (`servicePackagesID` ASC) VISIBLE,
  INDEX `fk_ServicePackageDetails_services1_idx` (`serviceID` ASC) VISIBLE,
  CONSTRAINT `fk_ServicePackageDetails_ServicePackages1`
    FOREIGN KEY (`servicePackagesID`)
    REFERENCES `inhala`.`servicePackages` (`servicePackagesID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_ServicePackageDetails_services1`
    FOREIGN KEY (`serviceID`)
    REFERENCES `inhala`.`services` (`serviceID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_0900_ai_ci;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
