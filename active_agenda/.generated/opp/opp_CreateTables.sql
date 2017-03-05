CREATE TABLE `opp` (
   OppPermitID int unsigned auto_increment not null,
   PermitName varchar(128),
   OrganizationID int,
   PermitTypeID int unsigned not null,
   PermitDesc text,
   AgencyLevelID int,
   AgencyID int,
   CriticalPermit bool default 0,
   ActivePermit bool default 0,
   TransferRestrict bool default 0,
   ModifyRestrict bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      OppPermitID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `opp_l` (
   _RecordID int unsigned not null auto_increment,
   OppPermitID int unsigned  not null,
   PermitName varchar(128),
   OrganizationID int,
   PermitTypeID int unsigned not null,
   PermitDesc text,
   AgencyLevelID int,
   AgencyID int,
   CriticalPermit bool default 0,
   ActivePermit bool default 0,
   TransferRestrict bool default 0,
   ModifyRestrict bool default 0,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      OppPermitID
   )
) TYPE=InnoDB;
