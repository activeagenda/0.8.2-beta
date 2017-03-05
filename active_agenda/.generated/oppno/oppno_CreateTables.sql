CREATE TABLE `oppno` (
   OppNoID int unsigned auto_increment not null,
   OppPermitID int,
   AgencyID int,
   NumberTypeID int,
   Number varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OppNoID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppno_l` (
   _RecordID int unsigned not null auto_increment,
   OppNoID int unsigned  not null,
   OppPermitID int,
   AgencyID int,
   NumberTypeID int,
   Number varchar(50),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OppNoID
   )
) TYPE=InnoDB;
