CREATE TABLE `hwk` (
   HotWorkID int unsigned auto_increment not null,
   OrganizationID int,
   HotWorkTypeID int unsigned not null,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      HotWorkID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `hwk_l` (
   _RecordID int unsigned not null auto_increment,
   HotWorkID int unsigned  not null,
   OrganizationID int,
   HotWorkTypeID int unsigned not null,
   HotWorkTitle varchar(128),
   HotWorkDesc text,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      HotWorkID
   )
) TYPE=InnoDB;
