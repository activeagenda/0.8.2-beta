CREATE TABLE `cod` (
   CodeID int not null,
   CodeTypeID int not null,
   SortOrder int,
   Value varchar(25),
   Description varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CodeTypeID,
      CodeID
   ),
   INDEX cod_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `cod_l` (
   _RecordID int unsigned not null auto_increment,
   CodeID int not null,
   CodeTypeID int not null,
   SortOrder int,
   Value varchar(25),
   Description varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      CodeTypeID,
      CodeID
   ),
   INDEX cod_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
