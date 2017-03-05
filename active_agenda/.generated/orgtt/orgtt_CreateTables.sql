CREATE TABLE `orgtt` (
   JobTitleTypeID int unsigned auto_increment not null,
   JobTitleCategoryID int,
   JobTitleTypeTitle varchar(128),
   JobTitleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      JobTitleTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `orgtt_l` (
   _RecordID int unsigned not null auto_increment,
   JobTitleTypeID int unsigned  not null,
   JobTitleCategoryID int,
   JobTitleTypeTitle varchar(128),
   JobTitleTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      JobTitleTypeID
   )
) TYPE=InnoDB;
