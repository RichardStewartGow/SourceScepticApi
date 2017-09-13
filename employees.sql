CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `companyId` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `employees` (`id`, `firstName`, `lastName`, `companyId`) VALUES
(1, 'test', 'man', 1),
(2, 'test2', 'man2', 1),
(3, 'its', 'working', 1);

ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyIdIndex` (`companyId`);

ALTER TABLE `employees`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

