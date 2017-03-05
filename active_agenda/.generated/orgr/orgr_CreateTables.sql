CREATE TABLE `orgr` (
   RequirementID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   RequirementTitle varchar(128),
   RequirementPurpose text,
   RequirementDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      RequirementID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgr_l` (
   _RecordID int unsigned not null auto_increment,
   RequirementID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   RequirementTitle varchar(128),
   RequirementPurpose text,
   RequirementDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      RequirementID
   )
) TYPE=InnoDB;
