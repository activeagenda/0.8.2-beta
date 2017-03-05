CREATE TABLE `chmco` (
   ChemCompID int unsigned auto_increment not null,
   ChemicalID int,
   ChemCompName varchar(128),
   ComponentCASNumber varchar(50),
   PercentByWeight int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemCompID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmco_l` (
   _RecordID int unsigned not null auto_increment,
   ChemCompID int unsigned  not null,
   ChemicalID int,
   ChemCompName varchar(128),
   ComponentCASNumber varchar(50),
   PercentByWeight int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemCompID
   )
) TYPE=InnoDB;
