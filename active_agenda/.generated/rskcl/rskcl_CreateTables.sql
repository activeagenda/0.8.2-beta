CREATE TABLE `rskcl` (
   RiskClassID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RiskClassificationID int,
   RiskClassDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RiskClassID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskcl_l` (
   _RecordID int unsigned not null auto_increment,
   RiskClassID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   RiskClassificationID int,
   RiskClassDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RiskClassID
   )
) TYPE=InnoDB;
