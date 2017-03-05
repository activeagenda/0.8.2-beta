CREATE TABLE `oppp` (
   PermitPenaltyID int unsigned auto_increment not null,
   OppPermitID int,
   PenaltyTypeID int,
   PenaltyDesc text,
   PenaltyAmount decimal(12,4),
   PenaltyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitPenaltyID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppp_l` (
   _RecordID int unsigned not null auto_increment,
   PermitPenaltyID int unsigned  not null,
   OppPermitID int,
   PenaltyTypeID int,
   PenaltyDesc text,
   PenaltyAmount decimal(12,4),
   PenaltyUnitID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitPenaltyID
   )
) TYPE=InnoDB;
