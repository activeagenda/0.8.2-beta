CREATE TABLE `was` (
   WasteID int unsigned auto_increment not null,
   OrganizationID int,
   WasteTypeID int unsigned not null,
   WasteGenerationMethodID int,
   Solid bool default 0,
   Liquid bool default 0,
   Gas bool default 0,
   WasteName varchar(128),
   WasteDesc text,
   DisposalApproachID int,
   ChemicalInvolved bool default 0,
   ChemicalID int,
   SystemProcessInvolved bool default 0,
   SystemID int,
   LocalWasteIDs text,
   OrgWasteIDs text,
   AmtGenYear float,
   AmtGenYearUnitsID int,
   LocalDisposalPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `was_l` (
   _RecordID int unsigned not null auto_increment,
   WasteID int unsigned  not null,
   OrganizationID int,
   WasteTypeID int unsigned not null,
   WasteGenerationMethodID int,
   Solid bool default 0,
   Liquid bool default 0,
   Gas bool default 0,
   WasteName varchar(128),
   WasteDesc text,
   DisposalApproachID int,
   ChemicalInvolved bool default 0,
   ChemicalID int,
   SystemProcessInvolved bool default 0,
   SystemID int,
   LocalWasteIDs text,
   OrgWasteIDs text,
   AmtGenYear float,
   AmtGenYearUnitsID int,
   LocalDisposalPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteID
   )
) TYPE=InnoDB;
