CREATE TABLE `oppoa` (
   PermitOrgsID int unsigned auto_increment not null,
   OppPermitID int,
   OrganizationID int,
   Manner text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitOrgsID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppoa_l` (
   _RecordID int unsigned not null auto_increment,
   PermitOrgsID int unsigned  not null,
   OppPermitID int,
   OrganizationID int,
   Manner text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitOrgsID
   )
) TYPE=InnoDB;
