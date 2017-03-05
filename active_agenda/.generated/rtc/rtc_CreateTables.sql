CREATE TABLE `rtc` (
   CauseID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CausationTypeID int unsigned not null,
   RootCauseTitle varchar(128),
   RootCauseDesc text,
   RootCauseMethodID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CauseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rtc_l` (
   _RecordID int unsigned not null auto_increment,
   CauseID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   CausationTypeID int unsigned not null,
   RootCauseTitle varchar(128),
   RootCauseDesc text,
   RootCauseMethodID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CauseID
   )
) TYPE=InnoDB;
