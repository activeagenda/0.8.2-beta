CREATE TABLE `codt` (
   CodeTypeID int not null auto_increment,
   Name varchar(128),
   Description varchar(255),
   UseValue bool default 0 not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      CodeTypeID
   ),
   INDEX codt_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `codt_l` (
   _RecordID int unsigned not null auto_increment,
   CodeTypeID int not null ,
   Name varchar(128),
   Description varchar(255),
   UseValue bool default 0 not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   _GlobalID varchar(20) default NULL,
   PRIMARY KEY(
      _RecordID,
      CodeTypeID
   ),
   INDEX codt_l_GlobalID (
      _GlobalID
   )
) TYPE=InnoDB;
