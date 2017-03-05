CREATE TABLE `filt` (
   FileTypeID int unsigned auto_increment not null,
   FileCategoryID int,
   FileTypeTitle varchar(128),
   FileTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      FileTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `filt_l` (
   _RecordID int unsigned not null auto_increment,
   FileTypeID int unsigned  not null,
   FileCategoryID int,
   FileTypeTitle varchar(128),
   FileTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      FileTypeID
   )
) TYPE=InnoDB;
