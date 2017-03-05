CREATE TABLE `rsp` (
   ResponsibilityID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   OrgLevelID int,
   ResponsibilityTitle varchar(128),
   OrgResponsibility text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResponsibilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rsp_l` (
   _RecordID int unsigned not null auto_increment,
   ResponsibilityID int unsigned  not null,
   RelatedModuleID varchar(5),
   RelatedRecordID int,
   OrganizationID int,
   OrgLevelID int,
   ResponsibilityTitle varchar(128),
   OrgResponsibility text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResponsibilityID
   )
) TYPE=InnoDB;
