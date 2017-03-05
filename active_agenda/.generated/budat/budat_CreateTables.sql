CREATE TABLE `budat` (
   AccountTypeID int unsigned auto_increment not null,
   AccountCategoryID int,
   AccountTypeTitle varchar(128),
   AccountDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      AccountTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `budat_l` (
   _RecordID int unsigned not null auto_increment,
   AccountTypeID int unsigned  not null,
   AccountCategoryID int,
   AccountTypeTitle varchar(128),
   AccountDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      AccountTypeID
   )
) TYPE=InnoDB;
