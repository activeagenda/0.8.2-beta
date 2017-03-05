CREATE TABLE `rspc` (
   ResponsibilityConsidID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   OrgLevelID int,
   ResponsibilityConsidTitle varchar(128),
   ResponsibilityConsidDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResponsibilityConsidID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rspc_l` (
   _RecordID int unsigned not null auto_increment,
   ResponsibilityConsidID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   OrgLevelID int,
   ResponsibilityConsidTitle varchar(128),
   ResponsibilityConsidDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResponsibilityConsidID
   )
) TYPE=InnoDB;
