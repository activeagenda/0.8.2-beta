CREATE TABLE `jana` (
   JobAnalysisAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   JobAnalysisID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobAnalysisAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `jana_l` (
   _RecordID int unsigned not null auto_increment,
   JobAnalysisAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   JobAnalysisID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobAnalysisAssocID
   )
) TYPE=InnoDB;
