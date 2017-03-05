CREATE TABLE `docm` (
   DocumentationModuleID int unsigned auto_increment not null,
   DocumentID int,
   ModuleID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentationModuleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docm_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentationModuleID int unsigned  not null,
   DocumentID int,
   ModuleID varchar(5),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentationModuleID
   )
) TYPE=InnoDB;
