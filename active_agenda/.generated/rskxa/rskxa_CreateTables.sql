CREATE TABLE `rskxa` (
   IndexAssociationID int unsigned auto_increment not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LikelihoodID int,
   LikelihoodFactors text,
   LikelihoodStatusID int,
   SeverityID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IndexAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskxa_l` (
   _RecordID int unsigned not null auto_increment,
   IndexAssociationID int unsigned  not null,
   SourceModuleID varchar(5),
   SourceRecordID int,
   LikelihoodID int,
   LikelihoodFactors text,
   LikelihoodStatusID int,
   SeverityID int,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IndexAssociationID
   )
) TYPE=InnoDB;
