CREATE TABLE `docr` (
   DocumentationRecID int unsigned auto_increment not null,
   DocumentID int,
   OrganizationID int,
   ReceivingPreferenceID int,
   SubImmediacyValue float,
   SubImmediacyID int,
   ImmediacyDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      DocumentationRecID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `docr_l` (
   _RecordID int unsigned not null auto_increment,
   DocumentationRecID int unsigned  not null,
   DocumentID int,
   OrganizationID int,
   ReceivingPreferenceID int,
   SubImmediacyValue float,
   SubImmediacyID int,
   ImmediacyDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      DocumentationRecID
   )
) TYPE=InnoDB;
