CREATE TABLE `oppsa` (
   PermitSystemID int unsigned auto_increment not null,
   OppPermitID int,
   SystemID int,
   PermitSystemEffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitSystemID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppsa_l` (
   _RecordID int unsigned not null auto_increment,
   PermitSystemID int unsigned  not null,
   OppPermitID int,
   SystemID int,
   PermitSystemEffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitSystemID
   )
) TYPE=InnoDB;
