CREATE TABLE `sit` (
   SituationID int unsigned auto_increment not null,
   SituationTypeID int,
   LocalDescription text,
   OrganizationID int,
   DepartmentID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      SituationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `sit_l` (
   _RecordID int unsigned not null auto_increment,
   SituationID int unsigned  not null,
   SituationTypeID int,
   LocalDescription text,
   OrganizationID int,
   DepartmentID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      SituationID
   )
) TYPE=InnoDB;
