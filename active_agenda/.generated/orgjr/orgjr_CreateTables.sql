CREATE TABLE `orgjr` (
   JobReqID int unsigned auto_increment not null,
   OrgJobTitleID int unsigned,
   JobRequirementID int unsigned,
   JobReqDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobReqID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgjr_l` (
   _RecordID int unsigned not null auto_increment,
   JobReqID int unsigned  not null,
   OrgJobTitleID int unsigned,
   JobRequirementID int unsigned,
   JobReqDesc varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobReqID
   )
) TYPE=InnoDB;
