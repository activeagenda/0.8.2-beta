CREATE TABLE `oppcr` (
   PermitCondID int unsigned auto_increment not null,
   OppPermitID int,
   PermitRuleNo varchar(50),
   CondReq text,
   RefNo varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      PermitCondID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `oppcr_l` (
   _RecordID int unsigned not null auto_increment,
   PermitCondID int unsigned  not null,
   OppPermitID int,
   PermitRuleNo varchar(50),
   CondReq text,
   RefNo varchar(128),
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      PermitCondID
   )
) TYPE=InnoDB;
