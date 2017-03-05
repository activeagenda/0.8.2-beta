CREATE TABLE `moddr` (
   ModuleDirectionID int unsigned auto_increment not null,
   RelatedModuleID varchar(5),
   OrganizationID int,
   DirectionTitle varchar(128),
   Direction text,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleDirectionID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `moddr_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleDirectionID int unsigned  not null,
   RelatedModuleID varchar(5),
   OrganizationID int,
   DirectionTitle varchar(128),
   Direction text,
   SortOrder int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleDirectionID
   )
) TYPE=InnoDB;
