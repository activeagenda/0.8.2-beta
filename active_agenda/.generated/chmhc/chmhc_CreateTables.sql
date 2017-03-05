CREATE TABLE `chmhc` (
   ChemicalHazardClassificationID int unsigned auto_increment not null,
   ClassificationTypeID int,
   ClassificationTitle varchar(128),
   ClassificationNumber varchar(10),
   ClassificationDesc text,
   ClassificationOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalHazardClassificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmhc_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalHazardClassificationID int unsigned  not null,
   ClassificationTypeID int,
   ClassificationTitle varchar(128),
   ClassificationNumber varchar(10),
   ClassificationDesc text,
   ClassificationOrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalHazardClassificationID
   )
) TYPE=InnoDB;
