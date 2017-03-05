CREATE TABLE `ewkt` (
   ElevatedWorkTypeID int unsigned auto_increment not null,
   ElevatedWorkCategoryID int,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ElevatedWorkTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ewkt_l` (
   _RecordID int unsigned not null auto_increment,
   ElevatedWorkTypeID int unsigned  not null,
   ElevatedWorkCategoryID int,
   ElevatedWorkTitle varchar(128),
   ElevatedWorkDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ElevatedWorkTypeID
   )
) TYPE=InnoDB;
