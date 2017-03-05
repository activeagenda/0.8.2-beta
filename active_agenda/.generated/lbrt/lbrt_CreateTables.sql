CREATE TABLE `lbrt` (
   LineBreakTypeID int unsigned auto_increment not null,
   LineBreakCategoryID int,
   LineBreakTitle varchar(128),
   LineBreakDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      LineBreakTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `lbrt_l` (
   _RecordID int unsigned not null auto_increment,
   LineBreakTypeID int unsigned  not null,
   LineBreakCategoryID int,
   LineBreakTitle varchar(128),
   LineBreakDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      LineBreakTypeID
   )
) TYPE=InnoDB;
