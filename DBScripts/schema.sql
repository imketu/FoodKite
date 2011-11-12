SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `FoodkiteDB` ;
CREATE SCHEMA IF NOT EXISTS `FoodkiteDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `FoodkiteDB` ;

-- -----------------------------------------------------
-- Table `FoodkiteDB`.`PlaceType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`PlaceType` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`PlaceType` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Type` VARCHAR(45) NOT NULL ,
	`FoursquareCategoryId` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT=3000
;

CREATE INDEX `Name_indx` ON `FoodkiteDB`.`PlaceType` (`Type` ASC) ;
CREATE UNIQUE INDEX `FoursquareCategoryId_UNIQUE` ON `FoodkiteDB`.`PlaceType` (`FoursquareCategoryId` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`Place`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`Place` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`Place` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(45) NOT NULL ,
  `PlaceType` INT NOT NULL ,
  `FoursquareVenueId` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_Places_PlacesType`
    FOREIGN KEY (`PlaceType` )
    REFERENCES `FoodkiteDB`.`PlaceType` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT=9999
;

CREATE  INDEX `Name_INDX` ON `FoodkiteDB`.`Place` (`Name` ASC) ;

CREATE INDEX `fk_Place_PlaceType` ON `FoodkiteDB`.`Place` (`PlaceType` ASC) ;

CREATE UNIQUE INDEX `FoursquareVenueId_UNIQUE` ON `FoodkiteDB`.`Place` (`FoursquareVenueId` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`Menu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`Menu` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`Menu` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Place` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_Menu_Place`
    FOREIGN KEY (`Place` )
    REFERENCES `FoodkiteDB`.`Place` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT=4999
;

CREATE INDEX `fk_Menu_Place` ON `FoodkiteDB`.`Menu` (`Place` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`MenuType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`MenuType` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`MenuType` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Type` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT=3000
;

CREATE UNIQUE INDEX `Name_UNIQUE` ON `FoodkiteDB`.`MenuType` (`Type` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`Item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`Item` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`Item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Name` VARCHAR(255) NULL ,
  `Description` TINYTEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT=999
;

CREATE UNIQUE INDEX `name_UNIQUE` ON `FoodkiteDB`.`Item` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`SubMenu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`SubMenu` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`SubMenu` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Menu` INT NOT NULL ,
  `MenuType` INT NULL ,
  `SubMenu` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_SubMenu_Menu`
    FOREIGN KEY (`Menu` )
    REFERENCES `FoodkiteDB`.`Menu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT=999
;

CREATE INDEX `SubMenu_MenuType_INDEX` ON `FoodkiteDB`.`SubMenu` (`MenuType` ASC) ;

CREATE INDEX `fk_SubMenu_Menu` ON `FoodkiteDB`.`SubMenu` (`Menu` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`Dish`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`Dish` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`Dish` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `SubMenu` INT NULL ,
	`Menu` INT NOT NULL ,
  `Item` INT NOT NULL ,
  `Price` DOUBLE  NULL ,
  `Calories` DOUBLE NULL ,
  PRIMARY KEY (`id`) ,
	CONSTRAINT `fk_Dish_Menu`
    FOREIGN KEY (`Menu` )
    REFERENCES `FoodkiteDB`.`Menu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dish_SubMenu`
    FOREIGN KEY (`SubMenu` )
    REFERENCES `FoodkiteDB`.`SubMenu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Dish_Item`
    FOREIGN KEY (`Item` )
    REFERENCES `FoodkiteDB`.`Item` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT=999
;

CREATE INDEX `fk_Dish_Menu` ON `FoodkiteDB`.`Dish` (`Menu` ASC) ;

CREATE INDEX `fk_Dish_SubMenu` ON `FoodkiteDB`.`Dish` (`SubMenu` ASC) ;

CREATE INDEX `fk_Dish_Item` ON `FoodkiteDB`.`Dish` (`Item` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`User` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`User` (
  `UserId` INT NOT NULL AUTO_INCREMENT,
  `Handle` VARCHAR(45) NOT NULL ,
  `Pass` VARCHAR(45) NOT NULL ,
  `FoursquareId` VARCHAR(45) NULL ,
  `FacebookId` VARCHAR(45) NULL ,
  `TwitterId` VARCHAR(45) NULL ,
  `EmailId` VARCHAR(45) NULL ,
  PRIMARY KEY (`UserId`) )
ENGINE = InnoDB
AUTO_INCREMENT=1999
;

CREATE UNIQUE INDEX `Handle_UNIQUE` ON `FoodkiteDB`.`User` (`Handle` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`TuckIn`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`TuckIn` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`TuckIn` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Dish` INT NOT NULL ,
  `User_UserId` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_TuckIn_Dish`
    FOREIGN KEY (`Dish` )
    REFERENCES `FoodkiteDB`.`Dish` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_TuckIn_User`
    FOREIGN KEY (`User_UserId` )
    REFERENCES `FoodkiteDB`.`User` (`UserId` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_TuckIn_Dish` ON `FoodkiteDB`.`TuckIn` (`Dish` ASC) ;

CREATE INDEX `fk_TuckIn_User` ON `FoodkiteDB`.`TuckIn` (`User_UserId` ASC) ;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
