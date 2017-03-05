CREATE TABLE `res` (
   ResourceID int not null auto_increment,
   ResourceTypeID int,
   ResourceDesc text,
   ResourceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ResourceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `res_l` (
   _RecordID int unsigned not null auto_increment,
   ResourceID int not null ,
   ResourceTypeID int,
   ResourceDesc text,
   ResourceStatusID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ResourceID
   )
) TYPE=InnoDB;
