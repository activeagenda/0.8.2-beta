CREATE TABLE `ireps` (
   IRProductOrServiceID int unsigned auto_increment not null,
   IncidentReportID int,
   ProdServID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IRProductOrServiceID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ireps_l` (
   _RecordID int unsigned not null auto_increment,
   IRProductOrServiceID int unsigned  not null,
   IncidentReportID int,
   ProdServID int,
   MannerAffected text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IRProductOrServiceID
   )
) TYPE=InnoDB;
