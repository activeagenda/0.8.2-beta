CREATE TABLE `oppap` (
   PermitAppID int unsigned auto_increment not null,
   OppPermitID int,
   AppTypeID int,
   AppAppr bool default 0,
   AppExp date,
   ApplicationDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitAppID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppap_l` (
   _RecordID int unsigned not null auto_increment,
   PermitAppID int unsigned  not null,
   OppPermitID int,
   AppTypeID int,
   AppAppr bool default 0,
   AppExp date,
   ApplicationDetails text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitAppID
   )
) TYPE=InnoDB;
