CREATE TABLE `opdpt` (
   ProductServiceTypeID int unsigned auto_increment not null,
   ProductorServiceID int,
   ProductServiceCategoryID int,
   ProductServiceTypeTitle varchar(128),
   ProductServiceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ProductServiceTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opdpt_l` (
   _RecordID int unsigned not null auto_increment,
   ProductServiceTypeID int unsigned  not null,
   ProductorServiceID int,
   ProductServiceCategoryID int,
   ProductServiceTypeTitle varchar(128),
   ProductServiceTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ProductServiceTypeID
   )
) TYPE=InnoDB;
