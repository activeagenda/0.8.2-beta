CREATE TABLE `wasdf` (
   WasteDisposalFacilityID int unsigned auto_increment not null,
   WasteID int,
   DisposalFacilityID int,
   DisposalContractAgmt bool default 0,
   DisposalFacilityApproval bool default 0,
   DisposalPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteDisposalFacilityID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wasdf_l` (
   _RecordID int unsigned not null auto_increment,
   WasteDisposalFacilityID int unsigned  not null,
   WasteID int,
   DisposalFacilityID int,
   DisposalContractAgmt bool default 0,
   DisposalFacilityApproval bool default 0,
   DisposalPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteDisposalFacilityID
   )
) TYPE=InnoDB;
