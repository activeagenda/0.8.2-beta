CREATE TABLE `orgdp` (
   DepartmentID int unsigned auto_increment not null,
   OrganizationID int unsigned,
   FunctionID int unsigned,
   LevelID int unsigned,
   DepartmentName varchar(128) not null,
   DepartmentDesc text,
   DepartmentNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DepartmentID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgdp_l` (
   _RecordID int unsigned not null auto_increment,
   DepartmentID int unsigned  not null,
   OrganizationID int unsigned,
   FunctionID int unsigned,
   LevelID int unsigned,
   DepartmentName varchar(128) not null,
   DepartmentDesc text,
   DepartmentNo varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DepartmentID
   )
) TYPE=InnoDB;
