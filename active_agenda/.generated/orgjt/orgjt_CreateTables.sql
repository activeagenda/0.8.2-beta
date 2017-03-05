CREATE TABLE `orgjt` (
   OrgJobTitleID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   FunctionID int unsigned,
   LocalLevelID int unsigned,
   JobTitleTypeID int unsigned not null,
   JobTitleName varchar(128),
   JobDescription text,
   JobAuthority text,
   JobTitleNumber varchar(20),
   JobTitleStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OrgJobTitleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgjt_l` (
   _RecordID int unsigned not null auto_increment,
   OrgJobTitleID int unsigned  not null,
   OrganizationID int unsigned,
   FunctionID int unsigned,
   LocalLevelID int unsigned,
   JobTitleTypeID int unsigned not null,
   JobTitleName varchar(128),
   JobDescription text,
   JobAuthority text,
   JobTitleNumber varchar(20),
   JobTitleStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OrgJobTitleID
   )
) TYPE=InnoDB;
