CREATE TABLE `rskc` (
   ImperativeConsidID int unsigned auto_increment not null,
   GuidanceOrganizationID int unsigned not null,
   RiskImperativeID int,
   ImperativeConsidTitle varchar(128),
   ImperativeConsidDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      ImperativeConsidID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `rskc_l` (
   _RecordID int unsigned not null auto_increment,
   ImperativeConsidID int unsigned  not null,
   GuidanceOrganizationID int unsigned not null,
   RiskImperativeID int,
   ImperativeConsidTitle varchar(128),
   ImperativeConsidDescription text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      ImperativeConsidID
   )
) TYPE=InnoDB;
