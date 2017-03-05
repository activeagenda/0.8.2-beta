CREATE TABLE `smc` (
   CacheID int unsigned auto_increment not null,
   ModuleID varchar(5) not null,
   RecordID int not null,
   SubModuleID varchar(5) not null,
   SubRecordID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      CacheID
   ),
   INDEX smc_Parent (
      ModuleID,
      RecordID
   ),
   INDEX smc_Sub (
      SubModuleID,
      SubRecordID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `smc_l` (
   _RecordID int unsigned not null auto_increment,
   CacheID int unsigned  not null,
   ModuleID varchar(5) not null,
   RecordID int not null,
   SubModuleID varchar(5) not null,
   SubRecordID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      CacheID
   ),
   INDEX smc_l_Parent (
      ModuleID,
      RecordID
   ),
   INDEX smc_l_Sub (
      SubModuleID,
      SubRecordID
   )
) TYPE=InnoDB;
