CREATE TABLE `resm` (
   ModuleResourceID int not null auto_increment,
   ModuleDependencyID int,
   ModuleID varchar(5),
   ResourceID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleResourceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `resm_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleResourceID int not null ,
   ModuleDependencyID int,
   ModuleID varchar(5),
   ResourceID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleResourceID
   )
) TYPE=InnoDB;
