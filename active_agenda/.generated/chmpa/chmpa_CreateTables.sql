CREATE TABLE `chmpa` (
   ChemicalPhraseAssociationID int unsigned auto_increment not null,
   ChemicalID int,
   ChemicalPhraseID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ChemicalPhraseAssociationID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `chmpa_l` (
   _RecordID int unsigned not null auto_increment,
   ChemicalPhraseAssociationID int unsigned  not null,
   ChemicalID int,
   ChemicalPhraseID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ChemicalPhraseAssociationID
   )
) TYPE=InnoDB;
