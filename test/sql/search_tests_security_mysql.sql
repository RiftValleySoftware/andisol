DROP TABLE IF EXISTS co_security_nodes;
CREATE TABLE co_security_nodes (
  id bigint(20) UNSIGNED NOT NULL,
  api_key varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  login_id varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  access_class varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  last_access datetime NOT NULL,
  read_security_id bigint(20) DEFAULT NULL,
  write_security_id bigint(20) DEFAULT NULL,
  object_name varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  access_class_context mediumtext,
  ids varchar(4095) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  personal_ids varchar(4095) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO co_security_nodes (id, api_key, login_id, access_class, last_access, read_security_id, write_security_id, object_name, access_class_context, ids, personal_ids) VALUES
(1, NULL, NULL, 'CO_Security_Node', '1970-01-01 00:00:00', -1, -1, NULL, NULL, NULL, NULL),
(2, NULL, 'admin', 'CO_Security_Login', '1970-01-01 00:00:00', 1, 2, 'Default Admin', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:4:\"JUNK\";}', NULL, NULL),
(3, NULL, 'MDAdmin', 'CO_Cobra_Login', '1970-01-01 00:00:00', 1, 3, 'Maryland Login', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '', NULL),
(4, NULL, 'VAAdmin', 'CO_Cobra_Login', '1970-01-01 00:00:00', 1, 4, 'Virginia Login', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '', NULL),
(5, NULL, 'DCAdmin', 'CO_Cobra_Login', '1970-01-01 00:00:00', 1, 5, 'Washington DC Login', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '', NULL),
(6, NULL, 'WVAdmin', 'CO_Cobra_Login', '1970-01-01 00:00:00', 1, 6, 'West Virginia Login', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '', NULL),
(7, NULL, 'DEAdmin', 'CO_Cobra_Login', '1970-01-01 00:00:00', 1, 7, 'Delaware Login', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '', NULL),
(8, NULL, 'DCAreaManager', 'CO_Login_Manager', '1970-01-01 00:00:00', 1, 8, 'Manages All Logins', 'a:2:{s:4:"lang";s:2:"en";s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '2,3,4,5,6,7', NULL);

ALTER TABLE co_security_nodes
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY api_key (api_key),
  ADD UNIQUE KEY login_id (login_id),
  ADD KEY access_class (access_class),
  ADD KEY last_access (last_access),
  ADD KEY read_security_id (read_security_id),
  ADD KEY write_security_id (write_security_id),
  ADD KEY object_name (object_name);

ALTER TABLE co_security_nodes
  MODIFY id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;