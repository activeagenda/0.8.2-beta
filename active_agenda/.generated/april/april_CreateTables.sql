CREATE TABLE `april` (
   OtherItemLocationID int unsigned auto_increment not null,
   OtherItemID int,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OtherItemLocationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `april_l` (
   _RecordID int unsigned not null auto_increment,
   OtherItemLocationID int unsigned  not null,
   OtherItemID int,
   OrganizationID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OtherItemLocationID
   )
) TYPE=InnoDB;
