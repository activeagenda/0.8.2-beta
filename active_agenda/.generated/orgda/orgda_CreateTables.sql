CREATE TABLE `orgda` (
   DepartmentAssocID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   DepartmentID int,
   ResponsibilityTitle varchar(128),
   Responsibilities text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DepartmentAssocID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgda_l` (
   _RecordID int unsigned not null auto_increment,
   DepartmentAssocID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   DepartmentID int,
   ResponsibilityTitle varchar(128),
   Responsibilities text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DepartmentAssocID
   )
) TYPE=InnoDB;
