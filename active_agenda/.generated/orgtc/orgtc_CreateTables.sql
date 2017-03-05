CREATE TABLE `orgtc` (
   JobTitleConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   JobTitleConsiderationTitle varchar(128),
   JobTitleConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTitleConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgtc_l` (
   _RecordID int unsigned not null auto_increment,
   JobTitleConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   JobTitleConsiderationTitle varchar(128),
   JobTitleConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobTitleConsiderationID
   )
) TYPE=InnoDB;
