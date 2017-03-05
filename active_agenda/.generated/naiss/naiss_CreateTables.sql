CREATE TABLE `naiss` (
   IndustrySubSectorID int unsigned not null,
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustrySubSectorID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `naiss_l` (
   _RecordID int unsigned not null auto_increment,
   IndustrySubSectorID int unsigned not null,
   IndustrySectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndustrySubSectorID
   )
) TYPE=InnoDB;
