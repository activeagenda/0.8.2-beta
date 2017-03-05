CREATE TABLE `docsi` (
   DocumentationSituationID int unsigned auto_increment not null,
   DocumentID int,
   SituationTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentationSituationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docsi_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentationSituationID int unsigned  not null,
   DocumentID int,
   SituationTypeID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentationSituationID
   )
) TYPE=InnoDB;
