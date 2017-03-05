CREATE TABLE `modd` (
   ModuleDependencyID int unsigned auto_increment not null,
   ModuleID varchar(5),
   DependencyID varchar(5),
   ForeignDependency bool default 0,
   SubModDependency bool default 0,
   RemoteDependency bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ModuleDependencyID
   ),
   INDEX modd_ModuleID (
      ModuleID
   ),
   INDEX modd_DependencyID (
      DependencyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `modd_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleDependencyID int unsigned  not null,
   ModuleID varchar(5),
   DependencyID varchar(5),
   ForeignDependency bool default 0,
   SubModDependency bool default 0,
   RemoteDependency bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ModuleDependencyID
   ),
   INDEX modd_l_ModuleID (
      ModuleID
   ),
   INDEX modd_l_DependencyID (
      DependencyID
   )
) TYPE=InnoDB;
