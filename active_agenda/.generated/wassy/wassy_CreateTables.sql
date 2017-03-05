CREATE TABLE `wassy` (
   WasteSystemID int unsigned auto_increment not null,
   WasteID int,
   SystemID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteSystemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wassy_l` (
   _RecordID int unsigned not null auto_increment,
   WasteSystemID int unsigned  not null,
   WasteID int,
   SystemID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteSystemID
   )
) TYPE=InnoDB;
