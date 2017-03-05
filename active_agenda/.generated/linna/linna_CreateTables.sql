CREATE TABLE `linna` (
   InjuryNatureID int unsigned auto_increment not null,
   InjuryNatureTypeID int,
   InjuryNatureTitle varchar(128),
   InjuryNatureDivision varchar(10),
   InjuryNatureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      InjuryNatureID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linna_l` (
   _RecordID int unsigned not null auto_increment,
   InjuryNatureID int unsigned  not null,
   InjuryNatureTypeID int,
   InjuryNatureTitle varchar(128),
   InjuryNatureDivision varchar(10),
   InjuryNatureDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      InjuryNatureID
   )
) TYPE=InnoDB;
