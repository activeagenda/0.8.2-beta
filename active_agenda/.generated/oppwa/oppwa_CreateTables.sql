CREATE TABLE `oppwa` (
   PermitWasteID int unsigned auto_increment not null,
   OppPermitID int,
   WasteID int,
   PermitWasteAffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitWasteID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppwa_l` (
   _RecordID int unsigned not null auto_increment,
   PermitWasteID int unsigned  not null,
   OppPermitID int,
   WasteID int,
   PermitWasteAffect varchar(255),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitWasteID
   )
) TYPE=InnoDB;
