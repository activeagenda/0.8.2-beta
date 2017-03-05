CREATE TABLE `naig` (
   IndustryGroupID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustryGroupID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `naig_l` (
   _RecordID int unsigned not null auto_increment,
   IndustryGroupID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndustryGroupID
   )
) TYPE=InnoDB;
