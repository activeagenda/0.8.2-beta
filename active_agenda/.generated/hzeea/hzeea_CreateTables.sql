CREATE TABLE `hzeea` (
   EnergySourceAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   EnergySourceID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      EnergySourceAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzeea_l` (
   _RecordID int unsigned not null auto_increment,
   EnergySourceAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   EnergySourceID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      EnergySourceAssocID
   )
) TYPE=InnoDB;
