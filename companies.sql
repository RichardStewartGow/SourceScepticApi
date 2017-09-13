CREATE TABLE IF NOT EXISTS `companies` (
  `id` int(255) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `companies` (`id`, `name`) VALUES
(1, 'TestCompany'),
(2, 'second company');

ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `companies`
  MODIFY `id` int(255) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

