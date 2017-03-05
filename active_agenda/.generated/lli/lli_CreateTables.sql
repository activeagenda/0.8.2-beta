CREATE TABLE `lli` (
   LossLegitimacyID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LossLegitimacyTypeID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LossLegitimacyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lli_l` (
   _RecordID int unsigned not null auto_increment,
   LossLegitimacyID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LossLegitimacyTypeID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LossLegitimacyID
   )
) TYPE=InnoDB;
