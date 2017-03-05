CREATE TABLE `chmta` (
   ChemicalTypeAssociationID int unsigned auto_increment not null,
   ChemicalID int,
   ChemicalTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalTypeAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmta_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalTypeAssociationID int unsigned  not null,
   ChemicalID int,
   ChemicalTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalTypeAssociationID
   )
) TYPE=InnoDB;
