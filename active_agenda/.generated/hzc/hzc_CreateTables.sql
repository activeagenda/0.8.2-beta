CREATE TABLE `hzc` (
   HazardConsiderationID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   HazardConsiderationTitle varchar(128),
   HazardConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HazardConsiderationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hzc_l` (
   _RecordID int unsigned not null auto_increment,
   HazardConsiderationID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   HazardConsiderationTitle varchar(128),
   HazardConsiderationDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HazardConsiderationID
   )
) TYPE=InnoDB;
