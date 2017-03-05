CREATE TABLE `chmer` (
   ExposureRouteID int unsigned auto_increment not null,
   ChemicalID int,
   ChemicalExposureRouteID int,
   ExposureDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ExposureRouteID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmer_l` (
   _RecordID int unsigned not null auto_increment,
   ExposureRouteID int unsigned  not null,
   ChemicalID int,
   ChemicalExposureRouteID int,
   ExposureDetail text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ExposureRouteID
   )
) TYPE=InnoDB;
