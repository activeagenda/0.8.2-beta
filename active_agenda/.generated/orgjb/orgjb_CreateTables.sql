CREATE TABLE `orgjb` (
   JobResponsibilityID int unsigned auto_increment not null,
   OrgJobTitleID int unsigned not null,
   JobResponsibilityTypeID int unsigned,
   ResponsibilityTitle varchar(128),
   ResponsibilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobResponsibilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgjb_l` (
   _RecordID int unsigned not null auto_increment,
   JobResponsibilityID int unsigned  not null,
   OrgJobTitleID int unsigned not null,
   JobResponsibilityTypeID int unsigned,
   ResponsibilityTitle varchar(128),
   ResponsibilityDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobResponsibilityID
   )
) TYPE=InnoDB;
