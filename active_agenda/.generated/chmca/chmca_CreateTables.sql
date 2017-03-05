CREATE TABLE `chmca` (
   ChemicalHazardClassificationAssociationID int unsigned auto_increment not null,
   ChemicalID int,
   ChemicalHazardClassificationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalHazardClassificationAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmca_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalHazardClassificationAssociationID int unsigned  not null,
   ChemicalID int,
   ChemicalHazardClassificationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalHazardClassificationAssociationID
   )
) TYPE=InnoDB;
