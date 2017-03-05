CREATE TABLE `orgja` (
   JobTitleAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrgJobTitleID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTitleAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgja_l` (
   _RecordID int unsigned not null auto_increment,
   JobTitleAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrgJobTitleID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobTitleAssocID
   )
) TYPE=InnoDB;
