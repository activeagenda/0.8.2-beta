CREATE TABLE `lco` (
   LossCostID int unsigned auto_increment not null,
   ClaimID int unsigned not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ValuationDate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossCostID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lco_l` (
   _RecordID int unsigned not null auto_increment,
   LossCostID int unsigned  not null,
   ClaimID int unsigned not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   ValuationDate date,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossCostID
   )
) TYPE=InnoDB;
