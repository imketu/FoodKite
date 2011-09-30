SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `FoodkiteDB` ;
CREATE SCHEMA IF NOT EXISTS `FoodkiteDB` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `FoodkiteDB` ;

-- -----------------------------------------------------
-- Table `FoodkiteDB`.`PlaceType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`PlaceType` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`PlaceType` (
  `id` INT NOT NULL ,
  `Type` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `Name_UNIQUE` ON `FoodkiteDB`.`PlaceType` (`Type` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`Place`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`Place` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`Place` (
  `id` INT NOT NULL ,
  `Name` VARCHAR(45) NOT NULL ,
  `PlaceType` INT NOT NULL ,
  `FoursquareVenueId` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_Places_PlacesType`
    FOREIGN KEY (`PlaceType` )
    REFERENCES `FoodkiteDB`.`PlaceType` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE UNIQUE INDEX `Name_UNIQUE` ON `FoodkiteDB`.`Place` (`Name` ASC) ;

CREATE INDEX `fk_Place_PlaceType` ON `FoodkiteDB`.`Place` (`PlaceType` ASC) ;

CREATE UNIQUE INDEX `FoursquareVenueId_UNIQUE` ON `FoodkiteDB`.`Place` (`FoursquareVenueId` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`Menu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`Menu` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`Menu` (
  `id` INT NOT NULL ,
  `Menu` VARCHAR(255) NULL ,
  `Place` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_SubMenu_Place1`
    FOREIGN KEY (`Place` )
    REFERENCES `FoodkiteDB`.`Place` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_SubMenu_Place` ON `FoodkiteDB`.`Menu` (`Place` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`MenuType`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`MenuType` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`MenuType` (
  `id` INT NOT NULL ,
  `Type` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `Name_UNIQUE` ON `FoodkiteDB`.`MenuType` (`Type` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`Item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`Item` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`Item` (
  `id` INT NOT NULL ,
  `name` VARCHAR(255) NULL ,
  `Desctiption` TINYTEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `name_UNIQUE` ON `FoodkiteDB`.`Item` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`SubMenu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`SubMenu` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`SubMenu` (
  `id` INT NOT NULL ,
  `Menu` VARCHAR(255) NULL ,
  `MenueType` INT NOT NULL ,
  `SubMenu` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_Menu_MenuType`
    FOREIGN KEY (`MenueType` )
    REFERENCES `FoodkiteDB`.`MenuType` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Menu_SubMenu1`
    FOREIGN KEY (`SubMenu` )
    REFERENCES `FoodkiteDB`.`Menu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_Menu_MenueType` ON `FoodkiteDB`.`SubMenu` (`MenueType` ASC) ;

CREATE INDEX `fk_Menu_SubMenu` ON `FoodkiteDB`.`SubMenu` (`SubMenu` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`Dish`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`Dish` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`Dish` (
  `id` INT NOT NULL ,
  `SubMenu_id` INT NOT NULL ,
  `Item_idItem` INT NOT NULL ,
  `Price` DOUBLE NOT NULL ,
  `Calories` INT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `fk_SumbenueItemRelation_SubMenu1`
    FOREIGN KEY (`SubMenu_id` )
    REFERENCES `FoodkiteDB`.`SubMenu` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_SumbenueItemRelation_Item1`
    FOREIGN KEY (`Item_idItem` )
    REFERENCES `FoodkiteDB`.`Item` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `fk_Dish_SubMenu` ON `FoodkiteDB`.`Dish` (`SubMenu_id` ASC) ;

CREATE INDEX `fk_SumbenueItemRelation_Item` ON `FoodkiteDB`.`Dish` (`Item_idItem` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`User`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`User` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`User` (
  `UserId` INT NOT NULL ,
  `Handle` VARCHAR(45) NOT NULL ,
  `Pass` VARCHAR(45) NOT NULL ,
  `FoursquareId` VARCHAR(45) NULL ,
  `FacebookId` VARCHAR(45) NULL ,
  `TwitterId` VARCHAR(45) NULL ,
  `EmailId` VARCHAR(45) NULL ,
  PRIMARY KEY (`UserId`) )
ENGINE = InnoDB;

CREATE UNIQUE INDEX `Handle_UNIQUE` ON `FoodkiteDB`.`User` (`Handle` ASC) ;


-- -----------------------------------------------------
-- Table `FoodkiteDB`.`TuckIn`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `FoodkiteDB`.`TuckIn` ;

CREATE  TABLE IF NOT EXISTS `FoodkiteDB`.`TuckIn` (
  `id` INT NOT NULL ,
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
