CREATE TABLE `linnt` (
   InjuryNatureTypeID int unsigned auto_increment not null,
   InjuryNatureCategoryID int,
   NatureType varchar(128),
   InjuryNatureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryNatureTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linnt_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryNatureTypeID int unsigned  not null,
   InjuryNatureCategoryID int,
   NatureType varchar(128),
   InjuryNatureTypeDivision varchar(10),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryNatureTypeID
   )
) TYPE=InnoDB;
