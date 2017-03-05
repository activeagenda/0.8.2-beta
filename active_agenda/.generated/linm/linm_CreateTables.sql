CREATE TABLE `linm` (
   WorkModificationID int unsigned auto_increment not null,
   LossInjuryID int,
   ModificationTypeID int,
   AuthorizingOrganizationID int,
   DisabilitySlipAttached bool default 0,
   DisabilityDescription text,
   DaysNotScheduled int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WorkModificationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `linm_l` (
   _RecordID int unsigned not null auto_increment,
   WorkModificationID int unsigned  not null,
   LossInjuryID int,
   ModificationTypeID int,
   AuthorizingOrganizationID int,
   DisabilitySlipAttached bool default 0,
   DisabilityDescription text,
   DaysNotScheduled int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WorkModificationID
   )
) TYPE=InnoDB;
