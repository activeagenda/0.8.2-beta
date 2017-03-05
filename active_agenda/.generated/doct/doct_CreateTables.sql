CREATE TABLE `doct` (
   DocumentTypeID int unsigned auto_increment not null,
   DocumentCategoryID int,
   DocumentTypeName varchar(128),
   DocumentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentTypeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `doct_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentTypeID int unsigned  not null,
   DocumentCategoryID int,
   DocumentTypeName varchar(128),
   DocumentTypeDesc text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentTypeID
   )
) TYPE=InnoDB;
