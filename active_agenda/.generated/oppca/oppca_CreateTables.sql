CREATE TABLE `oppca` (
   PermitChemicalID int unsigned auto_increment not null,
   OppPermitID int,
   ChemicalInventoryID int,
   PermitChemicalAffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitChemicalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppca_l` (
   _RecordID int unsigned not null auto_increment,
   PermitChemicalID int unsigned  not null,
   OppPermitID int,
   ChemicalInventoryID int,
   PermitChemicalAffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitChemicalID
   )
) TYPE=InnoDB;
