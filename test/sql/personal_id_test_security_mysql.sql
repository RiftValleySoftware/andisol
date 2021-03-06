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
(2, NULL, 'admin', 'CO_Login_Manager', '1970-01-01 00:00:00', 2, 2, 'Default Admin', 'a:1:{s:15:\"hashed_password\";s:4:\"JUNK\";}', NULL, NULL),
(3, NULL, 'secondary', 'CO_Cobra_Login', '1970-01-01 00:00:00', 3, 3, 'Secondary Login', 'a:1:{s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '2,4,5,6', '8,9'),
(4, NULL, 'tertiary', 'CO_Login_Manager', '1970-01-01 00:00:00', 4, 4, 'Tertiary Login', 'a:1:{s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '6,9', '10,11'),
(5, NULL, 'four', 'CO_Cobra_Login', '1970-01-01 00:00:00', 5, 5, 'Admin Login 4', 'a:1:{s:15:\"hashed_password\";s:13:\"CodYOzPtwxb4A\";}', '3,2,7,11', NULL),
(6, NULL, NULL, 'CO_Security_ID', '1970-01-01 00:00:00', -1, -1, 'Security ID 6', '', NULL, NULL),
(7, NULL, NULL, 'CO_Security_ID', '1970-01-01 00:00:00', -1, -1, 'Security ID 7', '', NULL, NULL),
(8, NULL, NULL, 'CO_Security_ID', '1970-01-01 00:00:00', -1, -1, 'Security ID 8', '', NULL, NULL),
(9, NULL, NULL, 'CO_Security_ID', '1970-01-01 00:00:00', -1, -1, 'Security ID 9', '', NULL, NULL),
(10, NULL, NULL, 'CO_Security_ID', '1970-01-01 00:00:00', -1, -1, 'Security ID 10', '', NULL, NULL),
(11, NULL, NULL, 'CO_Security_ID', '1970-01-01 00:00:00', -1, -1, 'Security ID 11', '', NULL, NULL);

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
  MODIFY id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;