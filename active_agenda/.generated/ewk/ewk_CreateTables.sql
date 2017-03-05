CREATE TABLE `ewk` (
   ElevatedWorkID int unsigned auto_increment not null,
   OrganizationID int,
   ElevatedWorkTypeID int unsigned not null,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ElevatedWorkID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ewk_l` (
   _RecordID int unsigned not null auto_increment,
   ElevatedWorkID int unsigned  not null,
   OrganizationID int,
   ElevatedWorkTypeID int unsigned not null,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   EmergencyPhone varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ElevatedWorkID
   )
) TYPE=InnoDB;
