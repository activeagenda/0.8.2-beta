CREATE TABLE `orgra` (
   RequirementAccID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   RequirementAccTitle varchar(128),
   RequirementAccDesc text,
   PertinentInfo text,
   RequirementAccStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RequirementAccID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgra_l` (
   _RecordID int unsigned not null auto_increment,
   RequirementAccID int unsigned  not null,
   OrganizationID int unsigned,
   RequirementAccTitle varchar(128),
   RequirementAccDesc text,
   PertinentInfo text,
   RequirementAccStatusID int unsigned,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RequirementAccID
   )
) TYPE=InnoDB;
