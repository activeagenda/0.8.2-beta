CREATE TABLE `rsk` (
   ImperativeID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RiskImperativeID int,
   RiskDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ImperativeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rsk_l` (
   _RecordID int unsigned not null auto_increment,
   ImperativeID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RiskImperativeID int,
   RiskDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ImperativeID
   )
) TYPE=InnoDB;
