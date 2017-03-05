CREATE TABLE `nais` (
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustrySectorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `nais_l` (
   _RecordID int unsigned not null auto_increment,
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndustrySectorID
   )
) TYPE=InnoDB;
