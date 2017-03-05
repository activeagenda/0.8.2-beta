CREATE TABLE `bcqc` (
   BusinessConsequenceConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   BusinessConsequenceConsiderationTitle varchar(128),
   BusinessConsequenceConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      BusinessConsequenceConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `bcqc_l` (
   _RecordID int unsigned not null auto_increment,
   BusinessConsequenceConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   BusinessConsequenceConsiderationTitle varchar(128),
   BusinessConsequenceConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      BusinessConsequenceConsiderationID
   )
) TYPE=InnoDB;
