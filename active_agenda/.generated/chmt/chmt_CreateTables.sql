CREATE TABLE `chmt` (
   ChemicalTypeID int unsigned auto_increment not null,
   ChemicalCategoryID int,
   ChemicalTypeName varchar(128),
   ChemicalTypeDesc text,
   ChemicalTypeColor varchar(128),
   ApplyColorToLabel bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmt_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalTypeID int unsigned  not null,
   ChemicalCategoryID int,
   ChemicalTypeName varchar(128),
   ChemicalTypeDesc text,
   ChemicalTypeColor varchar(128),
   ApplyColorToLabel bool,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalTypeID
   )
) TYPE=InnoDB;
