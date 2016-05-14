--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `username`, `password`, `is_active`, `firstname`, `lastname`, `birthdate`, `sex`, `entry`, `contract`, `duration`, `salary`, `superior`) VALUES
(1, 'admin@admin.com', 'admin', '$2y$13$evJMovXyWKTVPt/A3mBvL.Q6dJAtlx3DqAtvahzfHxPX0L4nAn6m2', 1, 'admin', 'admin', '1896-01-01', 'M', '2011-01-01', 'CDI', 0, 120, 0);

--
-- Dumping data for table `app_role`
--

INSERT INTO `app_role` (`id`, `name`) VALUES
(1, 'ROLE_ADMIN');

--
-- Dumping data for table `app_users_roles`
--

INSERT INTO `app_users_roles` (`user_id`, `role_id`) VALUES
(1, 1);
