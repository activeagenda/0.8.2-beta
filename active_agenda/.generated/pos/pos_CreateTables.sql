CREATE TABLE `pos` (
   PostingID int unsigned auto_increment not null,
   OrganizationID int,
   PostingTypeID int,
   PostingName varchar(255),
   CurrentVersion varchar(128),
   PostingDesc text,
   PostingURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PostingID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `pos_l` (
   _RecordID int unsigned not null auto_increment,
   PostingID int unsigned  not null,
   OrganizationID int,
   PostingTypeID int,
   PostingName varchar(255),
   CurrentVersion varchar(128),
   PostingDesc text,
   PostingURL varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PostingID
   )
) TYPE=InnoDB;
