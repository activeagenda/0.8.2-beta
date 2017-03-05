CREATE TABLE `icta` (
   IncentiveAwardID int unsigned auto_increment not null,
   IncentiveAssocID int,
   ActualValue decimal(12,4),
   AwardOrganizationID int,
   ActivityDesc text,
   SubmittalDate datetime,
   ReceivedDate datetime,
   Awarded bool default 1,
   DenialReason text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      IncentiveAwardID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `icta_l` (
   _RecordID int unsigned not null auto_increment,
   IncentiveAwardID int unsigned  not null,
   IncentiveAssocID int,
   ActualValue decimal(12,4),
   AwardOrganizationID int,
   ActivityDesc text,
   SubmittalDate datetime,
   ReceivedDate datetime,
   Awarded bool default 1,
   DenialReason text,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      IncentiveAwardID
   )
) TYPE=InnoDB;
