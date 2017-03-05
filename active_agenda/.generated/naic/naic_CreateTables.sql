CREATE TABLE `naic` (
   IndustryCodeID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   IndustryGroupID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndustryCodeID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `naic_l` (
   _RecordID int unsigned not null auto_increment,
   IndustryCodeID int unsigned not null,
   IndustrySectorID int unsigned not null,
   IndustrySubSectorID int unsigned not null,
   IndustryGroupID int unsigned not null,
   Title varchar(128) not null,
   Description text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndustryCodeID
   )
) TYPE=InnoDB;
