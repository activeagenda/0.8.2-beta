CREATE TABLE `ntfap` (
   NotificationApproverID int unsigned auto_increment not null,
   OrganizationID int unsigned not null,
   RecipientOrganizationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      NotificationApproverID
   )
) TYPE=InnoDB;

-- statement separator --


CREATE TABLE `ntfap_l` (
   _RecordID int unsigned not null auto_increment,
   NotificationApproverID int unsigned  not null,
   OrganizationID int unsigned not null,
   RecipientOrganizationID int unsigned not null,
   _ModDate datetime not null,
   _ModBy int unsigned not null default 0,
   _Deleted bool not null default 0,
   PRIMARY KEY(
      _RecordID,
      NotificationApproverID
   )
) TYPE=InnoDB;
