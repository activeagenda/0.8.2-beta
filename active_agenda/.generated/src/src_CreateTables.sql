CREATE TABLE `src` (
   ModuleID varchar(5) not null,
   UserID int not null,
   Expression text not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      UserID,
      ModuleID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `src_l` (
   _RecordID int unsigned not null auto_increment,
   ModuleID varchar(5) not null,
   UserID int not null,
   Expression text not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      UserID,
      ModuleID
   )
) TYPE=InnoDB;
