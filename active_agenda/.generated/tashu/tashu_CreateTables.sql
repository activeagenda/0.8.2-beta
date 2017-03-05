CREATE TABLE `tashu` (
   HandUseID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   HandUseTitle varchar(128),
   UseofHandsID int,
   HandUseDuration float,
   TimeUnitID int,
   Frequency float,
   HandUseDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HandUseID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `tashu_l` (
   _RecordID int unsigned not null auto_increment,
   HandUseID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   HandUseTitle varchar(128),
   UseofHandsID int,
   HandUseDuration float,
   TimeUnitID int,
   Frequency float,
   HandUseDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HandUseID
   )
) TYPE=InnoDB;
