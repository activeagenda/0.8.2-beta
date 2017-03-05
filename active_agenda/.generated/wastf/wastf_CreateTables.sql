CREATE TABLE `wastf` (
   WasteTransferID int unsigned auto_increment not null,
   WasteID int,
   WasteTransporterID int,
   WasteShipAmt float,
   WasteShipAmtUnitsID int,
   Price decimal(12,4),
   PriceUnitID int,
   Revenue decimal(12,4),
   WasteDisposalFacilityID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      WasteTransferID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `wastf_l` (
   _RecordID int unsigned not null auto_increment,
   WasteTransferID int unsigned  not null,
   WasteID int,
   WasteTransporterID int,
   WasteShipAmt float,
   WasteShipAmtUnitsID int,
   Price decimal(12,4),
   PriceUnitID int,
   Revenue decimal(12,4),
   WasteDisposalFacilityID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      WasteTransferID
   )
) TYPE=InnoDB;
