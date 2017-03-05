CREATE TABLE `dld` (
   DownloadID int not null,
   FileTypeID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DownloadID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `dld_l` (
   _RecordID int unsigned not null auto_increment,
   DownloadID int not null,
   FileTypeID int not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DownloadID
   )
) TYPE=InnoDB;
