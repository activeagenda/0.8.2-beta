CREATE TABLE `wastp` (
   WasteTransporterID int unsigned auto_increment not null,
   WasteID int,
   TransporterID int,
   TransportContractAgmt bool default 0,
   TransporterFacilityApproval bool default 0,
   TransporterPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteTransporterID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wastp_l` (
   _RecordID int unsigned not null auto_increment,
   WasteTransporterID int unsigned  not null,
   WasteID int,
   TransporterID int,
   TransportContractAgmt bool default 0,
   TransporterFacilityApproval bool default 0,
   TransporterPractice text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteTransporterID
   )
) TYPE=InnoDB;
