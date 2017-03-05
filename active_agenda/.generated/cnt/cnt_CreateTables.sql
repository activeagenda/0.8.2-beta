CREATE TABLE `cnt` (
   ControlID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   AssignedOrganizationID int,
   ControlCategoryID int,
   ControlTitle varchar(128),
   ControlDesc text,
   ControlStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ControlID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cnt_l` (
   _RecordID int unsigned not null auto_increment,
   ControlID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   AssignedOrganizationID int,
   ControlCategoryID int,
   ControlTitle varchar(128),
   ControlDesc text,
   ControlStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ControlID
   )
) TYPE=InnoDB;
