CREATE TABLE `filr` (
   FileRecordID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FileRetentionID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `filr_l` (
   _RecordID int unsigned not null auto_increment,
   FileRecordID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   FileRetentionID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FileRecordID
   )
) TYPE=InnoDB;
